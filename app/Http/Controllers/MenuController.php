<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menus;
use Illuminate\Routing\Controller;

class MenuController extends Controller
{
    private function respond($state, $message, $data = null)
    {
        return response()->json(['state' => $state, 'message' => $message, 'data' => $data]);
    }

    public function getAllUpMenuData()
    {
        $menus = Menus::join('categories', 'menus.category_id', '=', 'categories.category_id')->select('menus.name', 'menus.price', 'categories.name as category')->where('status', '上架')->get();
        return $this->respond($menus->isNotEmpty(), $menus->isNotEmpty() ? '取得上架菜單資料成功' : '查無資料', $menus);
    }

    public function getAllMenuData()
    {
        $menus = Menus::join('categories', 'menus.category_id', '=', 'categories.category_id')
            ->select('menus.name', 'menus.price', 'categories.name as category', 'menus.status')
            ->get();
        return $this->respond($menus->isNotEmpty(), $menus->isNotEmpty() ? '取得上架菜單資料成功' : '查無資料', $menus);
    }

    public function selectCategoryData()
    {
        $categories = Menus::where('status', '上架')
            ->join('categories', 'menus.category_id', '=', 'categories.category_id')
            ->distinct()
            ->pluck('categories.name as category')
            ->map(function ($category) {
                return ['category' => $category];
            })->values();
        return $this->respond($categories->isNotEmpty(), $categories->isNotEmpty() ? '取得菜單分類成功' : '查無資料', $categories);
    }

    public function selectMenuFormData(Request $request)
    {
        $category = trim($request->input('category'));
        if (!$category) {
            return $this->respond(false, '欄位不能為空');
        }

        $menus = Menus::join('categories', 'menus.category_id', '=', 'categories.category_id')
            ->where('categories.name', $category)
            ->get();
        return $this->respond($menus->isNotEmpty(), $menus->isNotEmpty() ? '取得菜單資料成功' : '查無資料', $menus);
    }

    public function createMenuData(Request $request)
    {
        $name = trim($request->input('name'));
        $price = trim($request->input('price'));
        $category = trim($request->input('category'));
        $status = trim($request->input('status'));

        if (!$name || !$price || !$category || !$status) {
            return $this->respond(false, '欄位不能為空');
        }

        $categoryRecord = \App\Models\Categories::firstOrCreate(['name' => $category]);
        $menu = Menus::create([
            'name' => $name,
            'price' => $price,
            'category_id' => $categoryRecord->category_id,
            'status' => $status,
            'created_at' => now(),
        ]);
        return $this->respond(true, '菜單新增成功');
    }

    public function editMenuData(Request $request)
    {
        $name = trim($request->input('name'));
        $price = trim($request->input('price'));
        $category = trim($request->input('category'));
        $status = trim($request->input('status'));

        if (!$name || !$price || !$category || !$status) {
            return $this->respond(false, '欄位不能為空');
        }

        $categoryRecord = \App\Models\Categories::firstOrCreate(['name' => $category]);
        $menu = Menus::where('name', $name);
        if ($menu->exists()) {
            $menu->update([
                'name' => $name,
                'price' => $price,
                'category_id' => $categoryRecord->category_id,
                'status' => $status,
            ]);
            return $this->respond(true, '菜單更新成功');
        }
        return $this->respond(false, '菜單更新失敗，欄位錯誤');
    }

    public function checkMenuUni(Request $request)
    {
        $name = trim($request->input('name'));
        if (!$name) {
            return $this->respond(false, '欄位不得為空白');
        }

        $exists = Menus::where('name', $name)->exists();
        return $this->respond(!$exists, $exists ? '飲料名稱已存在，不可使用' : '飲料名稱不存在，可使用');
    }

    public function deleteMenuData(Request $request)
    {
        $name = trim($request->input('name'));
        if (!$name) {
            return $this->respond(false, '欄位不能為空');
        }

        $menu = Menus::where('name', $name)->first();
        if ($menu) {
            $menu->delete();
            return $this->respond(true, '菜單刪除成功');
        }
        return $this->respond(false, '菜單刪除失敗，名稱不存在');
    }
}
