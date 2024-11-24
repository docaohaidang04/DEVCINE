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

    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'product_combos', 'id_product', 'id_combo')
            ->withPivot('quantity');
    }

    public static function getAllProducts()
    {
        return self::all();
    }

    public static function getProductById($id)
    {
        return self::findOrFail($id);
    }

    public static function createProduct($request)
    {
        $data = self::validateRequest($request);

        if ($request->hasFile('image_product')) {
            $data['image_product'] = self::handleImageUpload($request->file('image_product'));
        }

        return self::create($data);
    }

    public static function updateProduct($id, $request)
    {
        $product = self::findOrFail($id);
        $data = self::validateRequest($request, 'update');

        if ($request->hasFile('image_product')) {
            if ($product->image_product && file_exists(public_path($product->image_product))) {
                unlink(public_path($product->image_product)); // Xóa ảnh cũ
            }
            $data['image_product'] = self::handleImageUpload($request->file('image_product'));
        }

        $product->update($data);
        return $product;
    }

    public static function deleteProduct($id)
    {
        $product = self::findOrFail($id);

        if ($product->image_product && file_exists(public_path($product->image_product))) {
            unlink(public_path($product->image_product)); // Xóa ảnh cũ
        }

        $product->delete();
    }

    private static function validateRequest($request, $context = 'create')
    {
        $rules = [
            'product_name' => ($context === 'create' ? 'required' : 'sometimes') . '|string|max:255',
            'description' => 'nullable|string',
            'price' => ($context === 'create' ? 'required' : 'sometimes') . '|numeric|min:0',
            'image_product' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'nullable|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $request->only(array_keys($rules));
    }

    private static function handleImageUpload($image)
    {
        $destinationPath = public_path('products');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true); // Tạo thư mục nếu chưa tồn tại
        }

        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move($destinationPath, $imageName);

        return 'products/' . $imageName; // Trả về đường dẫn ảnh
    }
}
