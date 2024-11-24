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

    // Quan hệ với Combo (nhiều sản phẩm thuộc về nhiều combo)
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

    public static function createProduct($request)
    {
        // Validate dữ liệu yêu cầu
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_product' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $data = $request->all();

        // Xử lý ảnh tải lên
        if ($request->hasFile('image_product')) {
            $destinationPath = public_path('products');
            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Tạo tên file ảnh duy nhất và di chuyển ảnh vào thư mục
            $imageProduct = time() . '_' . $request->file('image_product')->getClientOriginalName();
            $request->file('image_product')->move($destinationPath, $imageProduct);
            // Lưu đường dẫn ảnh
            $data['image_product'] = 'products/' . $imageProduct;
        }

        return self::create($data);
    }

    public static function getProductById($id)
    {
        return self::findOrFail($id);
    }

    public static function updateProduct($id, $request)
    {
        // Tìm sản phẩm hoặc trả về lỗi 404
        $product = self::findOrFail($id);

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
            if ($product->image_product && file_exists(public_path($product->image_product))) {
                unlink(public_path($product->image_product));
            }
        }

        // Cập nhật sản phẩm
        $product->update($data);

        return $product;
    }

    public static function deleteProduct($id)
    {
        $product = self::findOrFail($id);

        // Xóa ảnh cũ nếu có
        if ($product->image_product && file_exists(public_path($product->image_product))) {
            unlink(public_path($product->image_product));
        }

        // Xóa bản ghi sản phẩm
        $product->delete();
    }
}
