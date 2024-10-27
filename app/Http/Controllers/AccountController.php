<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $credentials = $request->only('user_name', 'password');

        $user = Account::loginAccount($credentials);

        if ($user) {
            return response()->json([
                'message' => 'Login successful',
                'user' => $user
            ]);
        }

        Log::error('Login failed for user: ' . $credentials['email']);
        return response()->json(['message' => 'Invalid credentials, please check your email and password.'], 401);
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
