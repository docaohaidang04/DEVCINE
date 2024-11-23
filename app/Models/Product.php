<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $primaryKey = 'id_product';

    protected $fillable = [
        'product_name',
        'description',
        'price',
        'is_active',
        'image_product',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getAllProducts()
    {
        return self::all();
    }

    public static function createProduct($request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_product' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);
    
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }
    
        $data = $request->all();
    
        // Lưu ảnh vào public/combos nếu có
        if ($request->hasFile('image_product')) {
            $destinationPath = public_path('combos');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $imageProduct = time() . '_' . $request->file('image_product')->getClientOriginalName();
            $request->file('image_product')->move($destinationPath, $imageProduct);
            $data['image_product'] = 'combos/' . $imageProduct;
        }
    
        return self::create($data);
    }

    public static function getProductById($id)
    {
        return self::findOrFail($id);
    }

    public static function updateProduct($id, $request)
    {
        $product = self::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'product_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'image_product' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $data = $request->all();

        // Cập nhật ảnh nếu có
        if ($request->hasFile('image_product')) {
            $destinationPath = public_path('combos');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $imageProduct = time() . '_' . $request->file('image_product')->getClientOriginalName();
            $request->file('image_product')->move($destinationPath, $imageProduct);
            $data['image_product'] = 'combos/' . $imageProduct;

            // Xóa ảnh cũ nếu có
            if ($product->image_product && file_exists(public_path($product->image_product))) {
                unlink(public_path($product->image_product));
            }
        }

        $product->update($data);
        return $product;
    }

    public static function deleteProduct($id)
    {
        $product = self::findOrFail($id);
        
        // Xóa ảnh nếu có
        if ($product->image_product && file_exists(public_path($product->image_product))) {
            unlink(public_path($product->image_product));
        }

        $product->delete();
    }
}
