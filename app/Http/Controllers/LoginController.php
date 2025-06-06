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
            return back()->withErrors(['errors' => 'Email cannot be empty'])->withInput();
        }
        if(!$password) {
            return back()->withErrors(['errors' => 'Password cannot be empty'])->withInput();
        }
        
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withErrors(['errors' => 'Invalid username/password'])->withInput();
        }

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = auth()->user();
            switch ($user->role) {
                case User::ROLE_CUSTOMER:
                    return redirect()->route('page.index');
                case User::ROLE_ADMIN:
                    return redirect()->route('admin.dashboard');
                case User::ROLE_SELLER:
                    return redirect()->route('admin.dashboard');
                default:
                    return redirect()->route('login');
            }
        }

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
        if(!$user) {
            session()->flash('error', 'Email không tồn tại trong hệ thống.');
            return redirect()->back();
        }
        $newPassword = Str::random(6);
        $hashPassword = Hash::make($newPassword);
        $user->update(['password' => $hashPassword]);

        SendResetPasswordEmail::dispatch($email, $user->name, $newPassword);
        session()->flash('status', 'Mật khẩu mới đã được gửi vào email của bạn.');
        return redirect()->route('login');
    }

    public function changePassword(Request $request)
    {
        $newPass = $request->input('new-password');
        $currentPass = $request->input('current-password');
        $confirmPass = $request->input('confirm-password');

        $user = auth()->user();
        if(!Hash::check($currentPass, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Current password is incorrect.'
            ]);
        }
        if($newPass != $confirmPass) {
            return response()->json([
                'status' => 'error',
                'message' => 'New password and Confirm Password are not the same.'
            ]);
        }
        $user->update([
            'password' => Hash::make($newPass)
        ]);
        AuditLog::create([
            'user_id' => $user->id,
            'event' => 'change_password',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Password updated successfully'
        // ]);
        
        return redirect()->back()->with('success', 'Password changed successfully!');
    }
}
