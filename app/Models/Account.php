<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    use HasFactory;

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
}