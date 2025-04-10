<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,user_id',
                'store_id' => 'required|integer|exists:stores,store_id',
                'total_price' => 'required|numeric|min:0',
                'order_details' => 'required|array|min:1',
                'order_details.*.menu_id' => 'required|integer|exists:menus,menu_id',
                'order_details.*.quantity' => 'required|integer|min:1'
            ]);

            DB::beginTransaction();

            // 使用資料庫中定義的 ENUM 值 '待支付'
            $order = Orders::create([
                'user_id' => $validated['user_id'],
                'store_id' => $validated['store_id'],
                'total_price' => $validated['total_price'],
                'status' => '待支付', // 修正為符合 ENUM 的值
                'order_date' => now()
            ]);

            foreach ($validated['order_details'] as $detail) {
                OrderDetails::create([
                    'order_id' => $order->order_id,
                    'menu_id' => $detail['menu_id'],
                    'quantity' => $detail['quantity']
                ]);
            }

            DB::commit();

            return response()->json([
                'state' => true,
                'message' => '訂單創建成功',
                'data' => $order
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'state' => false,
                'message' => '驗證失敗',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('訂單創建失敗: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'state' => false,
                'message' => '訂單創建失敗：' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    // 獲取用戶訂單
    public function userGetOrder(Request $request)
    {
        try {
            $validated = $request->validate(['user_id' => 'required|integer|exists:users,user_id']);
            $orders = Orders::with('store')
                            ->where('user_id', $validated['user_id'])
                            ->get()
                            ->map(function ($order) {
                                return [
                                    'order_id' => $order->order_id,
                                    'name' => $order->store->name,
                                    'address' => $order->store->address,
                                    'total_price' => $order->total_price,
                                    'status' => $order->status, // 直接使用資料庫值
                                    'order_date' => $order->order_date
                                ];
                            });

            return response()->json([
                'state' => $orders->isNotEmpty(),
                'message' => $orders->isNotEmpty() ? '取得訂單成功' : '無訂單記錄',
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            Log::error('獲取訂單失敗: ' . $e->getMessage());
            return response()->json([
                'state' => false,
                'message' => '獲取訂單失敗：' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    // 獲取訂單明細
    public function userGetOrderDetail(Request $request)
    {
        try {
            $validated = $request->validate(['order_id' => 'required|integer|exists:orders,order_id']);
            $details = OrderDetails::with('menu')
                                   ->where('order_id', $validated['order_id'])
                                   ->get()
                                   ->map(function ($detail) {
                                       return [
                                           'name' => $detail->menu->name,
                                           'price' => $detail->menu->price,
                                           'quantity' => $detail->quantity
                                       ];
                                   });

            return response()->json([
                'state' => true,
                'message' => '取得訂單明細成功',
                'data' => $details
            ]);
        } catch (\Exception $e) {
            Log::error('獲取訂單明細失敗: ' . $e->getMessage());
            return response()->json([
                'state' => false,
                'message' => '獲取訂單明細失敗：' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    // 編輯訂單狀態
    public function editOrderStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|integer|exists:orders,order_id',
                'status' => 'required|in:待支付,已支付,已取消'
            ]);

            $order = Orders::findOrFail($validated['order_id']);
            $order->status = $validated['status'];
            $order->save();

            return response()->json([
                'state' => true,
                'message' => '訂單狀態更新成功',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            Log::error('訂單狀態更新失敗: ' . $e->getMessage());
            return response()->json([
                'state' => false,
                'message' => '訂單狀態更新失敗：' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    // 獲取店家訂單
    public function getStoreOrders(Request $request)
    {
        try {
            $validated = $request->validate(['store_id' => 'required|integer|exists:stores,store_id']);
            $orders = Orders::with('orderDetails.menu')
                            ->where('store_id', $validated['store_id'])
                            ->get()
                            ->flatMap(function ($order) {
                                return $order->orderDetails->map(function ($detail) use ($order) {
                                    return [
                                        'order_id' => $order->order_id,
                                        'name' => $detail->menu->name,
                                        'status' => $order->status,
                                        'quantity' => $detail->quantity,
                                        'total_price' => $order->total_price,
                                        'order_date' => $order->order_date
                                    ];
                                });
                            });

            return response()->json([
                'state' => $orders->isNotEmpty(),
                'message' => $orders->isNotEmpty() ? '獲取訂單成功' : '無訂單記錄',
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            Log::error('獲取店家訂單失敗: ' . $e->getMessage());
            return response()->json([
                'state' => false,
                'message' => '獲取店家訂單失敗：' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    
}

