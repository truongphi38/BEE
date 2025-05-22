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
    // Gửi OTP qua email
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Email không hợp lệ'], 400);
        }

        $otp = rand(100000, 999999); // Tạo mã OTP

        // Lấy hoặc tạo user
        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => 'User ' . Str::random(5),
                'password' => Hash::make(Str::random(10)),
            ]
        );

        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        // Gửi mail
        try {
            Mail::raw("Mã OTP của bạn là: $otp", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('BeeShop - Mã xác thực OTP');
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể gửi email', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'OTP đã được gửi qua email']);
    }

    // Xác thực OTP
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
        $user->save();

        $token = $user->createToken('otp-login')->plainTextToken;

        return response()->json([
            'message' => 'Xác thực OTP thành công',
            'token' => $token,
        ]);
    }
}
