<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    private function respond($state, $message, $data = null)
    {
        return response()->json(['state' => $state, 'message' => $message, 'data' => $data]);
    }

    public function getAllUserData()
    {
        $users = Users::whereNotNull('users.store_id')
            ->join('stores', 'users.store_id', '=', 'stores.store_id')
            ->select('users.store_id', 'stores.name', 'users.username', 'users.email')
            ->orderBy('users.user_id')
            ->get();

        return $this->respond($users->isNotEmpty(), $users->isNotEmpty() ? '取得所有店長資料成功' : '查無資料', $users);
    }

    public function createAdminData(Request $request)
    {
        $store_id = trim($request->input('store_id'));
        $username = trim($request->input('username'));
        $password = trim($request->input('password'));
        $email = trim($request->input('email'));

        if (!$store_id || !$username || !$password || !$email) {
            return $this->respond(false, '欄位不能為空');
        }

        $user = Users::create([
            'store_id' => $store_id,
            'username' => $username,
            'password' => Hash::make($password),
            'email' => $email,
            'vip_level_id' => 5,
            'created_at' => now(),
        ]);
        return $this->respond(true, '新增店長成功');
    }

    public function editAdminData(Request $request)
    {
        $store_id = trim($request->input('store_id'));
        $username = trim($request->input('username'));

        if (!$store_id || !$username) {
            return $this->respond(false, '欄位不能為空');
        }

        $user = Users::where('username', $username)->first();
        if ($user) {
            $user->update(['store_id' => $store_id]);
            return $this->respond(true, '修改店長資料成功');
        }
        return $this->respond(false, '修改店長資料失敗，欄位錯誤');
    }

    public function deleteAdminData(Request $request)
    {
        $username = trim($request->input('username'));
        if (!$username) {
            return $this->respond(false, '欄位不能為空');
        }

        $user = Users::where('username', $username)->whereNotNull('store_id')->first();
        if ($user) {
            $user->delete();
            return $this->respond(true, '註銷店長資料成功');
        }
        return $this->respond(false, '註銷店長資料失敗，該用戶不是店長或不存在');
    }
}