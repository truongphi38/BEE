<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // Đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng ký thành công',
            'user'    => $user,
            'token'   => $token,
        ]);
    }

    // Đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Sai thông tin đăng nhập'], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user'    => $user,
            'token'   => $token,
        ]);
    }

    // Lấy thông tin người dùng hiện tại
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Đăng xuất thành công']);
    }

    // Cập nhật mật khẩu (khi đã đăng nhập)
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        $user = $request->user(); // Dùng $request->user() an toàn hơn Auth::user()

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Mật khẩu hiện tại không đúng'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Mật khẩu đã được cập nhật thành công']);
    }

    // Gửi mã OTP qua email
    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->email;
        $otp   = rand(100000, 999999);

        Cache::put("otp_$email", $otp, now()->addMinutes(5));

        Mail::raw("Mã OTP để đặt lại mật khẩu là: $otp", function ($message) use ($email) {
            $message->to($email)
                    ->subject('OTP đặt lại mật khẩu');
        });

        return response()->json(['message' => 'OTP đã được gửi tới email.']);
    }

    // Xác minh OTP
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp'   => 'required|digits:6',
        ]);

        $email     = $request->email;
        $otpInput  = $request->otp;
        $otpCached = Cache::get("otp_$email");

        if ($otpCached && $otpInput == $otpCached) {
            Cache::put("otp_verified_$email", true, now()->addMinutes(10));
            return response()->json(['message' => 'OTP hợp lệ']);
        }

        return response()->json(['message' => 'OTP không hợp lệ hoặc đã hết hạn'], 400);
    }

    // Đặt lại mật khẩu sau khi xác thực OTP
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'        => 'required|email|exists:users,email',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $email = $request->email;
        $isVerified = Cache::get("otp_verified_$email");

        if (!$isVerified) {
            return response()->json(['message' => 'OTP chưa được xác thực'], 403);
        }

        $user = User::where('email', $email)->first();
        $user->password = Hash::make($request->new_password);
        $user->save();

        Cache::forget("otp_$email");
        Cache::forget("otp_verified_$email");

        return response()->json(['message' => 'Mật khẩu đã được đặt lại thành công']);
    }
}
