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

        // Nếu dữ liệu không hợp lệ, ném ngoại lệ
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        // Lấy tất cả dữ liệu hợp lệ từ request
        $data = $validator->validated();

        // Xử lý ảnh nếu có
        if ($request->hasFile('image_product')) {
            $destinationPath = public_path('products'); // Thư mục lưu ảnh
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true); // Tạo thư mục nếu chưa tồn tại
            }

            $imageName = time() . '_' . $request->file('image_product')->getClientOriginalName();
            $request->file('image_product')->move($destinationPath, $imageName); // Lưu ảnh vào thư mục

            $data['image_product'] = 'products/' . $imageName; // Đường dẫn ảnh lưu trong DB
        }

        // Tạo sản phẩm mới
        return self::create($data);
    }


    public static function getProductById($id)
    {
        return self::findOrFail($id);
    }



    public static function updateProduct($id, $request)
    {
        // Tìm sản phẩm theo ID
        $product = self::findOrFail($id);

        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'product_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'image_product' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        // Ném lỗi nếu xác thực thất bại
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        // Lấy dữ liệu đã được xác thực
        $data = $validator->validated();

        // Xử lý ảnh nếu có file ảnh được tải lên
        if ($request->hasFile('image_product')) {
            $destinationPath = public_path('products');

            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Tạo tên file ảnh mới
            $imageName = time() . '_' . $request->file('image_product')->getClientOriginalName();
            $request->file('image_product')->move($destinationPath, $imageName);

            // Lưu đường dẫn ảnh mới
            $data['image_product'] = 'products/' . $imageName;

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
