<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    // 登入
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = Users::where('username', $validated['username'])->first();

        if ($user && Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'state' => true,
                'message' => '登入成功',
                'data' => $user->only(['user_id', 'username', 'Uid01', 'vip_level'])
            ]);
        }

        return response()->json([
            'state' => false,
            'message' => '帳號或密碼錯誤',
            'data' => null
        ]);
    }

    // 驗證 UID
    public function checkUid(Request $request)
    {
        $validated = $request->validate(['uid01' => 'required|string']);
        $user = Users::where('Uid01', $validated['uid01'])->first();

        return response()->json([
            'state' => $user ? true : false,
            'message' => $user ? '驗證成功' : '驗證失敗',
            'data' => $user ? $user->only(['user_id', 'store_id', 'username', 'email', 'vip_level', 'order_count', 'Uid01', 'created_at']) : null
        ]);
    }

    // 註冊
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|min:3|max:7|unique:users,username',
            'password' => 'required|string|min:3|max:6',
            'email' => 'required|email|min:3|max:20|unique:users,email',
        ]);

        $user = Users::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'email' => $validated['email'],
            'Uid01' => $this->generateUid(),
            'vip_level' => 0,
            'created_at' => now()
        ]);

        return response()->json([
            'state' => true,
            'message' => '註冊成功',
            'data' => ['Uid01' => $user->Uid01]
        ]);
    }

    // 檢查用戶名唯一性
    public function checkUsernameUnique(Request $request)
    {
        $validated = $request->validate(['username' => 'required|string']);
        $exists = Users::where('username', $validated['username'])->exists();

        return response()->json([
            'state' => !$exists,
            'message' => $exists ? '帳號已存在，不可使用' : '帳號不存在，可使用'
        ]);
    }

    private function generateUid()
    {
        return substr(hash('md5', time()), 10, 4) .
            substr(bin2hex(random_bytes(16)), 4, 4) .
            substr(hash('sha256', time()), 10, 4) .
            substr(hash('sha512', time()), 10, 4);
    }
}
