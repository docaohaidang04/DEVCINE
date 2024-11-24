<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Lấy danh sách sản phẩm
    public function index(Request $request)
    {
        // Gọi hàm trong Model và truyền request vào
        $products = Product::getProducts($request);

        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        try {
            $product = Product::createProduct($request);
            return response()->json([
                'product' => $product,
                'image_url' => asset($product->image_product),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }

        // Sử dụng phương thức tạo sản phẩm trong model
        $product = Product::createProduct($request);


        // Trả về response kèm dữ liệu sản phẩm và đường dẫn ảnh
        return response()->json([
            'product' => $product
        ], 201);
    }

    public function show($id)
    {
        try {
            $product = Product::getProductById($id);
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::updateProduct($id, $request);
            return response()->json($product, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->validator->errors(), 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function destroy($id)
    {
        try {
            Product::deleteProduct($id);
            return response()->json(['message' => 'Product deleted successfully'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
