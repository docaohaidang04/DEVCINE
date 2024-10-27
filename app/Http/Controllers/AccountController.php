<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function register(Request $request)
    {
        $account = Account::registerAccount($request->all());
        if ($account instanceof \Illuminate\Http\JsonResponse) {
            return $account; // Trả về lỗi nếu có
        }

        return response()->json($account, 201);
    }

    public function login(Request $request)
    {
        dd($request->all());
        $credentials = $request->only('email', 'password');

        if (Account::loginAccount($credentials)) {
            return response()->json(['message' => 'Login successful']);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
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