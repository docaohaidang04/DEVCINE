<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|unique:accounts',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|unique:accounts',
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'role' => 'nullable|string|in:user,admin',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $account = Account::create([
            'user_name' => $request->user_name,
            'password' => $request->password,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'role' => $request->role ?? 'user',
            'loyalty_points' => $request->loyalty_points ?? 0,
        ]);

        return response()->json($account, 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json(['message' => 'Login successful']);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function getAccount($id)
    {
        $account = Account::findOrFail($id);
        return response()->json($account);
    }

    public function updateAccount(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_name' => 'sometimes|string|unique:accounts,user_name,' . $id,
            'email' => 'sometimes|string|email|unique:accounts,email,' . $id,
            'full_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:15',
            'role' => 'sometimes|string|in:user,admin',
            'loyalty_points' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $account->update($request->all());
        return response()->json($account, 200);
    }

    public function deleteAccount($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();
        return response()->json(null, 204);
    }
}