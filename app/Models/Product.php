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

    public static function getProducts($request)
    {
        // Lấy giá trị 'is_active' từ query string
        $is_active = $request->query('is_active');

        // Nếu 'is_active' không null, chuyển đổi giá trị thành boolean
        if (!is_null($is_active)) {
            $is_active = filter_var($is_active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            // Nếu giá trị không hợp lệ, trả về danh sách rỗng hoặc xử lý lỗi
            if (is_null($is_active)) {
                return collect(); // Trả về danh sách rỗng
            }

            // Lọc sản phẩm theo trạng thái is_active
            return self::where('is_active', $is_active)->get();
        }

        // Nếu không lọc, trả về toàn bộ sản phẩm
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
        // Tìm sản phẩm hoặc trả về lỗi 404
        $product = self::findOrFail($id);
        $data = self::validateRequest($request, 'update');

<<<<<<< HEAD
        // Validate dữ liệu yêu cầu
        $validator = Validator::make($request->all(), [
            'product_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'image_product' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $data = $request->only(['product_name', 'description', 'price', 'is_active']); // Lấy dữ liệu hợp lệ

        // Xử lý ảnh nếu có ảnh mới được tải lên
        if ($request->hasFile('image_product')) {
            $image = $request->file('image_product');
            $imageFileName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('products');

            // Lưu ảnh mới
            $image->move($destinationPath, $imageFileName);
            $data['image_product'] = 'products/' . $imageFileName;

            // Xóa ảnh cũ nếu tồn tại
=======
        if ($request->hasFile('image_product')) {
>>>>>>> e52e039a5b60be645c6d323f10e948dd8a568770
            if ($product->image_product && file_exists(public_path($product->image_product))) {
                unlink(public_path($product->image_product)); // Xóa ảnh cũ
            }
            $data['image_product'] = self::handleImageUpload($request->file('image_product'));
        }

<<<<<<< HEAD
        // Cập nhật sản phẩm
=======
>>>>>>> e52e039a5b60be645c6d323f10e948dd8a568770
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
