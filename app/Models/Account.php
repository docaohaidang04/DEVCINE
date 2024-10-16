<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Account extends Authenticatable
{
    use HasFactory;

    protected $table = 'accounts';  // Chỉ định bảng

    protected $primaryKey = 'id_account';  // Đặt khóa chính là id_account

    protected $fillable = [
        'user_name',
        'password',
        'email',
        'full_name',
        'phone',
        'role',
        'loyalty_points',
    ];

    protected $hidden = [
        'password',
    ];

    // Mã hóa mật khẩu trước khi lưu
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    // Phương thức đăng ký tài khoản
    public static function registerAccount($data)
    {
        $validator = Validator::make($data, [
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

        return self::create($data);
    }

    // Phương thức đăng nhập
    public static function loginAccount($credentials)
    {
        return Auth::attempt($credentials);
    }

    // Phương thức lấy tài khoản
    public static function getAccountById($id)
    {
        return self::findOrFail($id);
    }

    // Phương thức cập nhật tài khoản
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

    // Phương thức xóa tài khoản
    public static function deleteAccount($id)
    {
        $account = self::findOrFail($id);
        $account->delete();
        return response()->json(null, 204);
    }
}
