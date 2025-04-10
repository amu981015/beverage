<?php

namespace App\Http\Controllers;

use App\Models\Stores;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StoreController extends Controller
{
    public function showMap()
    {
        $cities = Stores::select('city')->distinct()->pluck('city');
        return view('front.map', compact('cities'));
    }

    // 獲取所有城市
    public function getCities()
    {
        $cities = Stores::select('city')->distinct()->get();
        return response()->json([
            'state' => true,
            'message' => '取得城市成功',
            'data' => $cities
        ]);
    }

    // 根據城市獲取區域
    public function getAreas(Request $request)
    {
        $validated = $request->validate(['city' => 'required|string']);
        $areas = Stores::where('city', $validated['city'])
            ->select('area')
            ->distinct()
            ->get();
        return response()->json([
            'state' => true,
            'message' => '取得區域成功',
            'data' => $areas
        ]);
    }

    // 根據城市和區域獲取門市
    public function getStoresByCityAndArea(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string',
            'area' => 'required|string'
        ]);
        $stores = Stores::where('city', $validated['city'])
            ->where('area', $validated['area'])
            ->get(['store_id', 'name', 'address', 'tel', 'photo', 'latitude', 'longitude']);
        return response()->json([
            'state' => true,
            'message' => '取得門市成功',
            'data' => $stores
        ]);
    }

    // 獲取所有門市（之前用於 map.blade.php）
    public function getAllStores()
    {
        $stores = Stores::all();
        return response()->json([
            'state' => true,
            'message' => '取得所有門市資料成功',
            'data' => $stores
        ]);
    }

    public function checkStoreUnique(Request $request)
{
    $validated = $request->validate(['store_id' => 'required|integer']);
    $store = Stores::find($validated['store_id']);

    return response()->json([
        'state' => !!$store,
        'message' => $store ? '門市存在' : '門市不存在',
        'data' => $store ?: []
    ]);
}
}
