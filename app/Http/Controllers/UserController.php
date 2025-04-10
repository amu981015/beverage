<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function respond($state, $message, $data = null)
    {
        return response()->json(['state' => $state, 'message' => $message, 'data' => $data]);
    }

    public function register(Request $request)
    {
        $username = trim($request->input('username'));
        $password = trim($request->input('password'));
        $email = trim($request->input('email'));

        if (!$username || !$password || !$email) {
            return $this->respond(false, '欄位不能為空');
        }

        $user = Users::create([
            'username' => $username,
            'password' => Hash::make($password),
            'email' => $email,
            'vip_level_id' => 1,
            'Uid01' => substr(hash('md5', time()), 10, 4) . substr(bin2hex(random_bytes(16)), 4, 4) .
                substr(hash('sha256', time()), 10, 4) . substr(hash('sha512', time()), 10, 4),
            'created_at' => now(),
        ]);

        return $user ? $this->respond(true, '註冊成功', ['Uid01' => $user->Uid01]) : $this->respond(false, '註冊失敗');
    }

    public function login(Request $request)
    {
        $username = trim($request->input('username'));
        $password = trim($request->input('password'));

        if (!$username || !$password) {
            return $this->respond(false, '欄位不能為空');
        }

        $user = Users::where('username', $username)->first();
        if (!$user) {
            return $this->respond(false, '登入失敗，該帳號不存在或已被註銷');
        }

        if (Hash::check($password, $user->password)) {
            $user->Uid01 = substr(hash('md5', time()), 10, 4) . substr(bin2hex(random_bytes(16)), 4, 4) .
                substr(hash('sha256', time()), 10, 4) . substr(hash('sha512', time()), 10, 4);
            $user->save();
            return $this->respond(true, '登入成功', [
                'store_id' => $user->store_id,
                'username' => $user->username,
                'email' => $user->email,
                'vip_level' => $user->vipLevel->name,
                'Uid01' => $user->Uid01,
                'created_at' => $user->created_at,
            ]);
        }
        return $this->respond(false, '登入失敗，密碼錯誤');
    }

    public function checkUid(Request $request)
    {
        $uid01 = trim($request->input('uid01'));
        if (!$uid01) {
            return $this->respond(false, '欄位不能為空');
        }

        $user = Users::where('Uid01', $uid01)->first();
        if ($user) {
            return $this->respond(true, '驗證成功', [
                'user_id' => $user->user_id,
                'store_id' => $user->store_id,
                'username' => $user->username,
                'email' => $user->email,
                'vip_level' => $user->vipLevel->level_value,
                'Uid01' => $user->Uid01,
                'created_at' => $user->created_at,
            ]);
        }
        return $this->respond(false, '驗證失敗');
    }

    public function checkUni(Request $request)
    {
        $username = trim($request->input('username'));
        if (!$username) {
            return $this->respond(false, '欄位不得為空白');
        }

        $exists = Users::where('username', $username)->exists();
        return $this->respond(!$exists, $exists ? '帳號已存在，不可使用' : '帳號不存在，可使用');
    }
}
