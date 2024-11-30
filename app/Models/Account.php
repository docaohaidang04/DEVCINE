<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountVerificationMail;

class Account extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'accounts';

    protected $primaryKey = 'id_account';

    protected $fillable = [
        'user_name',
        'password',
        'email',
        'full_name',
        'phone',
        'reset_token',
        'reset_token_expires_at',
        'role',
        'loyalty_points',
        'refresh_token',
        'refresh_token_expires_at',
        'verification_token',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public static function registerAccount($data)
    {
        $validator = Validator::make($data, [
            'user_name' => 'required|string|unique:accounts',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|unique:accounts',
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'role' => 'string|in:user,admin',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!isset($data['role'])) {
            $data['role'] = 'user';
        }

        $data['verification_token'] = bin2hex(random_bytes(16));
        $account = self::create($data);

        // Gửi email xác nhận
        Mail::to($account->email)->send(new AccountVerificationMail($data['verification_token'], $account->email));

        return $account;
    }

    public static function verifyAccount($token)
    {
        $account = self::where('verification_token', $token)->first();
        if ($account) {
            $account->email_verified_at = now();
            $account->verification_token = null;
            $account->save();
            return $account;
        }
        return null;
    }

    public static function loginAccount($credentials)
    {
        if (Auth::attempt($credentials)) {
            return Auth::user();
        }
        return null;
    }

    public static function getAccountById($id)
    {
        return self::findOrFail($id);
    }

    public function updateAccount($data)
    {
        $validator = Validator::make($data, [
            'user_name' => 'sometimes|string|unique:accounts,user_name,' . $this->id_account,
            'email' => 'sometimes|string|email|unique:accounts,email,' . $this->id_account,
            'full_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:15',
            'role' => 'sometimes|string|in:user,admin',
            'loyalty_points' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->update($data);
        return $this;
    }

    public static function deleteAccount($id)
    {
        $account = self::findOrFail($id);
        $account->delete();
        return response()->json(null, 204);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
