<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\AuditLog;
use App\Jobs\SendResetPasswordEmail;
use Illuminate\Foundation\Bus\Dispatchable;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if(!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Email không được để trống'
            ]);
        }
        if(!$password) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu không được để trống'
            ]);
        }
        
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username/password'
            ]);
        }
        Auth::attempt(['email' => $email, 'password' => $password]);
        $user = auth()->user();
        switch ($user->role) {
            case User::ROLE_CUSTOMER:
                $redirect = route('page.index');
                break;
            case User::ROLE_ADMIN:
                $redirect = route('admin.dashboard');
                break;
            case User::ROLE_SELLER:
                $redirect = route('admin.dashboard');
                break;
            default:
                $redirect = route('page.index');
                break;
        }


        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'redirect' => $redirect
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('page.index');    
    }

    public function resetPassword(Request $request)
    {
        $email = $request->input('reset-password-email');
        $user = User::where('email', $email)->first();
        if(!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Email không được để trống'
            ]);
        }
        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email không tồn tại trong hệ thống.'
            ]);
        }
        $newPassword = Str::random(6);
        $hashPassword = Hash::make($newPassword);
        $user->update(['password' => $hashPassword]);

        SendResetPasswordEmail::dispatch($email, $user->name, $newPassword);
        
        return response()->json([
            'success' => true,
            'message' => 'Mật khẩu mới đã được gửi vào email của bạn.'
        ]);
    }

    public function changePassword(Request $request)
    {
        $newPass = $request->input('new-password');
        $currentPass = $request->input('current-password');
        $confirmPass = $request->input('confirm-password');

        $user = auth()->user();
        if(!Hash::check($currentPass, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu hiện tại không đúng'
            ]);
        }
        if($newPass != $confirmPass) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu mới và xác nhận mật khẩu không khớp'
            ]);
        }
        $user->update(['password' => Hash::make($newPass)]);
        AuditLog::create([
            'user_id' => $user->id,
            'event' => 'change_password',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Thay đổi mật khẩu thành công'
        ]);
    }
}
