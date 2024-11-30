<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function register(Request $request)
    {
        $account = Account::registerAccount($request->all());
        if ($account instanceof \Illuminate\Http\JsonResponse) {
            return $account; // Trả về lỗi nếu có
        }

        return response()->json([
            'message' => 'Tài khoản đã được tạo. Vui lòng kiểm tra email để xác thực tài khoản.',
            'account' => $account
        ], 201);
    }

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
