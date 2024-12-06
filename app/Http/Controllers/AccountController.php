<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Promotion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{

    public function redeemDiscountCode(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Bạn cần phải đăng nhập để đổi mã giảm giá.'], 401);
        }

        $request->validate([
            'promotion_id' => 'required|integer|exists:promotions,id_promotion',
        ]);

        $promotionId = $request->input('promotion_id');
        $accountId = Auth::id();

        $promotion = Promotion::find($promotionId);

        if (!$promotion) {
            return response()->json(['message' => 'Mã giảm giá không hợp lệ.'], 400);
        }
        $account = Account::find($accountId);

        if ($account->loyalty_points < $promotion->promotion_point) {
            return response()->json(['message' => 'Bạn không đủ điểm thưởng để đổi mã giảm giá này.'], 400);
        }

        Account::where('id_account', $accountId)
            ->update(['loyalty_points' => $account->loyalty_points - $promotion->promotion_point]);

        DB::table('account_promotion')->insert([
            'account_id' => $accountId,
            'promotion_id' => $promotionId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Đổi mã giảm giá thành công.',
            'loyalty_points' => $account->loyalty_points - $promotion->promotion_point,
            'promotion' => $promotion
        ]);
    }


    public function register(Request $request)
    {
        $account = Account::registerAccount($request->all());
        if ($account instanceof \Illuminate\Http\JsonResponse) {
            return $account; // Trả về lỗi nếu có
        }

        return response()->json([
            'status' => 201,
            'message' => 'Tài khoản đã được tạo. Vui lòng kiểm tra email để xác thực tài khoản.',
            'account' => $account
        ], 201);
    }

    /* public function login(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('user_name', 'password');


        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->email_verified_at === null) {
                Auth::logout();
                return response()->json(['message' => 'Vui lòng xác thực email của bạn.'], 401);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Đăng nhập thành công',
                'user' => $user,
            ]);
        }

        return response()->json(['status' => 401, 'message' => 'Tài khoản hoặc mật khẩu không đúng'], 401);
    } */

    public function verify($token)
    {
        $account = Account::verifyAccount($token);
        if ($account) {
            return response()->json(['message' => 'Xác thực email thành công.']);
        }
        return response()->json(['message' => 'Mã xác thực không hợp lệ.'], 400);
    }

    public function showAllAccount()
    {
        $accounts = Account::all();
        return response()->json($accounts, 200);
    }

    public function getAccount($id)
    {
        $account = Account::getAccountById($id);
        return response()->json($account);
    }

    public function updateAccount(Request $request, $id)
    {
        $account = Account::getAccountById($id);
        $updatedAccount = $account->updateAccount($request->all());
        if ($updatedAccount instanceof \Illuminate\Http\JsonResponse) {
            return $updatedAccount; // Trả về lỗi nếu có
        }

        return response()->json($updatedAccount, 200);
    }

    public function deleteAccount($id)
    {
        return Account::deleteAccount($id);
    }
}
