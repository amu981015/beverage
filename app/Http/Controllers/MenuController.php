<?php

namespace App\Http\Controllers;

use App\Models\Menus;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MenuController extends Controller
{
    // 獲取所有分類
    public function getCategories()
    {
        $categories = Menus::select('category')->distinct()->get();
        return response()->json([
            'state' => true,
            'message' => '取得分類成功',
            'data' => $categories
        ]);
    }

    public function getupCategories()
    {
        $categories = Menus::select('category')->where('status', '上架')->distinct()->get();
        return response()->json([
            'state' => true,
            'message' => '取得分類成功',
            'data' => $categories
        ]);
    }

    // 根據分類獲取菜單項目
    public function getMenusByCategory(Request $request)
    {
        $validated = $request->validate(['category' => 'required|string']);
        $menus = Menus::where('category', $validated['category'])
            ->where('status', '上架')
            ->get(['menu_id', 'name', 'price', 'category', 'status']);
        return response()->json([
            'state' => true,
            'message' => '取得菜單成功',
            'data' => $menus
        ]);
    }

    public function getAllMenus()
    {
        $menus = Menus::all(['menu_id', 'name', 'price', 'category', 'status']);
        return response()->json([
            'state' => true,
            'message' => '取得所有菜單成功',
            'data' => $menus
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:8|unique:menus',
            'price' => 'required|numeric|max:200',
            'category' => 'required|max:5',
            'status' => 'required|in:上架,下架'
        ]);

        $menu = Menus::create($validated);
        return response()->json([
            'state' => true,
            'message' => '菜單新增成功',
            'data' => $menu
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,menu_id',
            'name' => 'required|max:8',
            'price' => 'required|numeric|max:200',
            'category' => 'required|max:5',
            'status' => 'required|in:上架,下架'
        ]);

        $menu = Menus::findOrFail($validated['menu_id']);
        $menu->update($validated);

        return response()->json([
            'state' => true,
            'message' => '菜單修改成功'
        ]);
    }

    public function checkMenuUnique(Request $request)
    {
        $validated = $request->validate(['name' => 'required|max:8']);
        $exists = Menus::where('name', $validated['name'])->exists();

        return response()->json([
            'state' => !$exists,
            'message' => $exists ? '名稱已存在' : '名稱可用'
        ]);
    }

    public function getAllUpMenus()
    {
        $menus = Menus::where('status', '上架')->get()->groupBy('category');
        return view('front.menu', compact('menus'));
    }
}
