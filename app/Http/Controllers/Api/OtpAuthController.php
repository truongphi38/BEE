<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OtpAuthController extends Controller
{
    // Gửi OTP và mật khẩu nếu là user mới (Đăng ký)
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Email không hợp lệ', 'errors' => $validator->errors()], 400);
        }

        // Kiểm tra email đã tồn tại
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json(['message' => 'Email đã được sử dụng'], 400);
        }

        $otp = rand(100000, 999999); // Tạo mã OTP
        $password = Str::random(8);  // Mật khẩu ngẫu nhiên

        // Tạo user mới
        $user = User::create([
            'email' => $request->email,
            'name' => 'User ' . Str::random(5),
            'password' => Hash::make($password),
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Gửi mail chứa OTP + mật khẩu
        try {
            $content = "BeeShop - Thông tin đăng ký:\n";
            $content .= "Mã OTP của bạn là: $otp\n";
            $content .= "Mật khẩu đăng nhập: $password";

            Mail::raw($content, function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('BeeShop - Mã OTP & Mật khẩu đăng nhập');
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể gửi email', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'OTP và mật khẩu đã được gửi qua email']);
    }

    // Xác thực OTP (Đăng nhập)
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Thông tin không hợp lệ'], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
        }

        if ($user->otp_code != $request->otp) {
            return response()->json(['message' => 'Mã OTP không đúng'], 400);
        }

        if (Carbon::now()->gt($user->otp_expires_at)) {
            return response()->json(['message' => 'Mã OTP đã hết hạn'], 400);
        }

        // Xác thực thành công
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->email_verified_at = now();
        $user->save();

        $token = $user->createToken('otp-login')->plainTextToken;

        return response()->json([
            'message' => 'Xác thực OTP thành công',
            'token' => $token,
        ]);
    }

    // Gửi OTP cho quên mật khẩu
    public function sendForgotPasswordOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Email không hợp lệ', 'errors' => $validator->errors()], 400);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email không tồn tại'], 404);
        }

        $otp = rand(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        try {
            $content = "BeeShop - Mã OTP đặt lại mật khẩu của bạn là: $otp\nMã có hiệu lực trong 10 phút.";
            Mail::raw($content, function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('BeeShop - Mã OTP đặt lại mật khẩu');
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể gửi email', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'OTP đã được gửi qua email']);
    }

    // Xác thực OTP và đổi mật khẩu mới (Quên mật khẩu)
    public function verifyForgotPasswordOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'new_password' => 'required|string|min:6|confirmed', // new_password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Thông tin không hợp lệ', 'errors' => $validator->errors()], 400);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
        }

        if ($user->otp_code != $request->otp) {
            return response()->json(['message' => 'Mã OTP không đúng'], 400);
        }

        if (Carbon::now()->gt($user->otp_expires_at)) {
            return response()->json(['message' => 'Mã OTP đã hết hạn'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Đổi mật khẩu thành công']);
    }
}
