<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Lấy danh sách sản phẩm
    public function index()
    {
        $products = Product::getAllProducts();
        return response()->json($products);
    }

    // Thêm sản phẩm mới
    public function store(Request $request)
    {
        Log::info('Request data:', $request->all());

        // Validate các dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_product' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        // Kiểm tra nếu validation thất bại
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Sử dụng phương thức tạo sản phẩm trong model
        $product = Product::createProduct($request);

        Log::info('Add product with data:', $request->all());

        // Trả về response kèm dữ liệu sản phẩm và đường dẫn ảnh
        return response()->json([
            'product' => $product,
            'image_url' => asset($product->image_product),
        ], 201);
    }

    // Lấy thông tin sản phẩm
    public function show($id)
    {
        $product = Product::getProductById($id);
        return response()->json($product);
    }

    // Cập nhật sản phẩm
    public function update(Request $request, $id)
    {
        try {
            $product = Product::updateProduct($id, $request->all());
            return response()->json($product, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->validator->errors(), 422);
        }
    }

    // Xóa sản phẩm
    public function destroy($id)
    {
        Product::deleteProduct($id);
        return response()->json(null, 204);
    }
}
