<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\OrderDetails;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function respond($state, $message, $data = null)
    {
        return response()->json(['state' => $state, 'message' => $message, 'data' => $data]);
    }

    public function createOrder(Request $request)
    {
        $user_id = $request->input('user_id');
        $store_id = $request->input('store_id');
        $order_details = $request->input('order_details');

        if (!$user_id || !$store_id || empty($order_details)) {
            return $this->respond(false, '必填欄位不能為空或訂單明細無效');
        }

        DB::beginTransaction();
        try {
            $order = Orders::create([
                'store_id' => $store_id,
                'user_id' => $user_id,
                'status' => '待支付',
                'order_date' => now(),
            ]);

            foreach ($order_details as $detail) {
                $menu = \App\Models\Menus::find($detail['menu_id']);
                OrderDetails::create([
                    'order_id' => $order->order_id,
                    'menu_id' => $detail['menu_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $menu->price,
                ]);
            }

            DB::commit();
            return $this->respond(true, '訂單處理成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->respond(false, $e->getMessage());
        }
    }

    public function getOrderData(Request $request)
    {
        $store_id = trim($request->input('store_id'));
        if (!$store_id) {
            return $this->respond(false, '欄位不能為空');
        }

        $orders = Orders::where('orders.store_id', $store_id)
    ->join('order_details', 'orders.order_id', '=', 'order_details.order_id')
    ->join('menus', 'order_details.menu_id', '=', 'menus.menu_id')
    ->join('stores', 'orders.store_id', '=', 'stores.store_id')
    ->select(
        'orders.order_id',
        'stores.name as store_name',
        'orders.status',
        'menus.name',
        'order_details.quantity',
        DB::raw('SUM(order_details.quantity * order_details.price) as total_price'),
        'orders.order_date',
        'order_details.detail_id'
    )
    ->groupBy(
        'orders.order_id', 
        'stores.name', 
        'orders.status', 
        'menus.name', 
        'order_details.quantity', 
        'orders.order_date',
        'order_details.detail_id'
    )
    ->orderBy('orders.order_id')
    ->orderBy('order_details.detail_id')
    ->get();

        return $this->respond($orders->isNotEmpty(), $orders->isNotEmpty() ? '取得商店訂單資料成功' : '查無資料', $orders);
    }

    public function editOrderStatusData(Request $request)
    {
        $order_id = trim($request->input('order_id'));
        $status = trim($request->input('status'));

        if (!$order_id || !$status) {
            return $this->respond(false, '欄位不能為空');
        }

        $order = Orders::find($order_id);
        if ($order && in_array($status, ['已支付', '已取消', '待支付'])) {
            $order->update(['status' => $status]);
            return $this->respond(true, '訂單狀態更新成功');
        }
        return $this->respond(false, '訂單狀態更新失敗，欄位錯誤');
    }

    public function userGetOrderData(Request $request)
    {
        $user_id = trim($request->input('user_id'));
        if (!$user_id) {
            return $this->respond(false, '欄位不能為空');
        }

        $orders = Orders::where('user_id', $user_id)
            ->join('stores', 'orders.store_id', '=', 'stores.store_id')
            ->join('order_details', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'orders.order_id',
                'orders.status',
                DB::raw('SUM(order_details.quantity * order_details.price) as total_price'),
                'orders.order_date',
                'stores.name',
                'stores.address'
            )
            ->groupBy('orders.order_id', 'orders.status', 'orders.order_date', 'stores.name', 'stores.address')
            ->get();

        return $this->respond($orders->isNotEmpty(), $orders->isNotEmpty() ? '取得用戶訂單資料成功' : '查無資料', $orders);
    }

    public function userGetOrderDetailData(Request $request)
    {
        $order_id = trim($request->input('order_id'));
        if (!$order_id) {
            return $this->respond(false, '欄位不能為空');
        }

        $details = OrderDetails::where('order_id', $order_id)
            ->join('menus', 'order_details.menu_id', '=', 'menus.menu_id')
            ->select('menus.name', 'menus.price', 'order_details.quantity')
            ->get();

        return $this->respond($details->isNotEmpty(), $details->isNotEmpty() ? '取得用戶訂單資料成功' : '查無資料', $details);
    }

    public function deleteOrderData(Request $request)
{
    $order_id = trim($request->input('order_id'));
    if (!$order_id) {
        return $this->respond(false, '欄位不能為空');
    }

    $order = Orders::find($order_id);
    if ($order) {
        $order->delete();
        return $this->respond(true, '訂單刪除成功');
    }
    return $this->respond(false, '訂單刪除失敗，訂單不存在');
}
}