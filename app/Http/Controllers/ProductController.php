<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
        try {
            $product = Product::createProduct($request->all());
            return response()->json($product, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->validator->errors(), 422);
        }
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
