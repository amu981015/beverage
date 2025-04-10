<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stores;
use Illuminate\Routing\Controller;

class StoreController extends Controller
{
    private function respond($state, $message, $data = null)
    {
        return response()->json(['state' => $state, 'message' => $message, 'data' => $data]);
    }

    public function getAllStoreData()
    {
        $stores = Stores::all();
        return $this->respond($stores->isNotEmpty(), $stores->isNotEmpty() ? '取得所有門市資料成功' : '查無資料', $stores);
    }

    public function selectCityData()
    {
        $cities = Stores::join('areas', 'stores.area_id', '=', 'areas.area_id')
            ->join('cities', 'areas.city_id', '=', 'cities.city_id')
            ->distinct()
            ->pluck('cities.name as city')
            ->map(function ($city) {
                return ['city' => $city];
            })->values();
        return $this->respond($cities->isNotEmpty(), $cities->isNotEmpty() ? '取得城市成功' : '查無資料', $cities);
    }

    public function selectAreaData(Request $request)
    {
        $city = trim($request->input('city'));
        if (!$city) {
            return $this->respond(false, '欄位不得為空白');
        }

        $areas = Stores::join('areas', 'stores.area_id', '=', 'areas.area_id')
            ->join('cities', 'areas.city_id', '=', 'cities.city_id')
            ->where('cities.name', $city)
            ->distinct()
            ->pluck('areas.name as area')
            ->map(function ($area) {
                return ['area' => $area];
            })->values();
        return $this->respond($areas->isNotEmpty(), $areas->isNotEmpty() ? '取得區域成功' : '查無資料', $areas);
    }

    public function selectStoreData(Request $request)
    {
        $city = trim($request->input('city'));
        $area = trim($request->input('area'));

        if (!$city || !$area) {
            return $this->respond(false, '欄位不得為空白');
        }

        $stores = Stores::join('areas', 'stores.area_id', '=', 'areas.area_id')
            ->join('cities', 'areas.city_id', '=', 'cities.city_id')
            ->where('cities.name', $city)
            ->where('areas.name', $area)
            ->get();
        return $this->respond($stores->isNotEmpty(), $stores->isNotEmpty() ? '取得門市資料成功' : '查無資料', $stores);
    }

    public function checkStoreUni(Request $request)
    {
        $store_id = trim($request->input('store_id'));
        if (!$store_id) {
            return $this->respond(false, '欄位不得為空白');
        }

        $store = Stores::where('store_id', $store_id)->first();
        return $this->respond(!!$store, $store ? '商店代碼存在，商店代稱：' : '商店代碼不存在，不可使用', $store ? ['name' => $store->name] : null);
    }

    public function deleteStoreData(Request $request)
    {
        $store_id = trim($request->input('store_id'));
        if (!$store_id) {
            return $this->respond(false, '欄位不能為空');
        }

        $store = Stores::where('store_id', $store_id)->first();
        if ($store) {
            $store->delete();
            return $this->respond(true, '店鋪刪除成功');
        }
        return $this->respond(false, '店鋪刪除失敗，商店代碼不存在');
    }
}
