<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        // Logging dữ liệu nhận được
         // Validate các dữ liệu đầu vào
          $request->validate([ 'product_name' => 'required|string|max:255', 'description' => 'nullable|string', 'price' => 'required|numeric|min:0', 'image_product' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 'is_active' => 'boolean', ]); 
          // Kiểm tra và tạo thư mục nếu chưa tồn tại
           $destinationPath = public_path('products'); if (!file_exists($destinationPath)) { mkdir($destinationPath, 0755, true); } 
           // Khởi tạo các biến để lưu tên tệp
            $imageProductName = null; 
            // Lưu ảnh vào public/products nếu có 
            if ($request->hasFile('image_product')) { $imageProductName = time() . '_' . $request->file('image_product')->getClientOriginalName(); $request->file('image_product')->move($destinationPath, $imageProductName); } 
            // Tạo mới bản ghi sản phẩm
             $product = Product::create([ 'product_name' => $request->product_name, 'description' => $request->description, 'price' => $request->price, 'is_active' => $request->is_active, 'image_product' => $imageProductName ? 'products/' . $imageProductName : null, ]);  
             Log::info('Add product with data:', $request->all()); 
             // Logging thông tin cập nhật
              // Trả về response kèm đường dẫn ảnh
               return response()->json([ 'product' => $product, 'image_url' => asset($product->image_product), ], 201); } 
               // Lấy một sản phẩm theo ID public 
               
    


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
