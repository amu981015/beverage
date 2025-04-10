<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getAllUsers()
    {
        $admins = Users::where('store_id', '!=', NULL)
            ->with('store') // 載入關聯的 store 資料
            ->get(['store_id', 'username', 'email']); // 不再選擇 Users 表中的 name 欄位

        // 設定需要顯示 store 的 name
        $admins->transform(function ($user) {
            $user->name = $user->store ? $user->store->name : null; // 若關聯存在，取得 store name
            return $user;
        });

        return response()->json([
            'state' => true,
            'message' => '取得店長資料成功',
            'data' => $admins
        ]);
    }

    // 新增店長
    public function createAdmin(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,store_id',
            'username' => 'required|max:12|unique:users',
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$/',
            'email' => 'required|email|unique:users'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['vip_level'] = 100; // 假設店長等級為 100
        $admin = Users::create($validated);

        return response()->json([
            'state' => true,
            'message' => '店長新增成功',
            'data' => $admin
        ]);
    }

    // 修改店長
    public function editAdmin(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|exists:users,username',
            'store_id' => 'required|exists:stores,store_id'
        ]);

        $admin = Users::where('username', $validated['username'])->firstOrFail();
        if ($admin->vip_level < 100) {
            return response()->json(['state' => false, 'message' => '該用戶不是店長']);
        }

        $admin->update(['store_id' => $validated['store_id']]);

        return response()->json([
            'state' => true,
            'message' => '店長修改成功'
        ]);
    }

    // 刪除店長
    public function deleteAdmin(Request $request)
    {
        $validated = $request->validate(['username' => 'required|exists:users,username']);
        $admin = Users::where('username', $validated['username'])->firstOrFail();

        if ($admin->vip_level < 100) {
            return response()->json(['state' => false, 'message' => '該用戶不是店長']);
        }

        $admin->delete();

        return response()->json([
            'state' => true,
            'message' => '店長已註銷'
        ]);
    }

    // 檢查用戶名是否唯一（新增路由）
    public function checkAdminUnique(Request $request)
    {
        if (trim($request) != null) {
            $validated = $request->validate(['username' => 'required|max:12']);
            $exists = Users::where('username', $validated['username'])->exists();

            return response()->json([
                'state' => !$exists,
                'message' => $exists ? '用戶名已存在' : '用戶名可用'
            ]);
        }else{
            return response()->json([
                'state' => false,
                'message' => "請輸入資訊!"
            ]);
        }
    }
}
