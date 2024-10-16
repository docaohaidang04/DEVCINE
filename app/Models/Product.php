<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'id_product'; // Đặt khóa chính là id_product

    protected $fillable = [
        'name',
        'description',
        'price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Lấy danh sách sản phẩm
    public static function getAllProducts()
    {
        return self::all();
    }

    // Thêm sản phẩm mới
    public static function createProduct($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return self::create($data);
    }

    // Lấy thông tin sản phẩm
    public static function getProductById($id)
    {
        return self::findOrFail($id);
    }

    // Cập nhật sản phẩm
    public static function updateProduct($id, $data)
    {
        $product = self::findOrFail($id);

        $validator = Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $product->update($data);
        return $product;
    }

    // Xóa sản phẩm
    public static function deleteProduct($id)
    {
        $product = self::findOrFail($id);
        $product->delete();
    }
}
