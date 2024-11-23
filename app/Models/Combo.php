<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Combo extends Model
{
    use HasFactory;

    protected $table = 'combos';
    protected $primaryKey = 'id_combo';

    protected $fillable = [
        'name',
        'price',
        'description',
        'image_combos',
        'created_at',
        'updated_at'
    ];

    // Quan hệ với sản phẩm (bỏ một định nghĩa products)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_combos', 'id_combo', 'id_product')
            ->withPivot('quantity');
    }

    // Lấy tất cả combos
    public static function getAllCombos()
    {
        return self::all();
    }

    // Tạo combo mới
    public static function createCombo($request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image_combos' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'products' => 'required|array', // Mảng sản phẩm
            'products.*.id_product' => 'required|exists:products,id_product', // ID sản phẩm phải tồn tại
            'products.*.quantity' => 'required|integer|min:1' // Số lượng sản phẩm phải > 0
        ]);

        // **Tính tổng giá combo**
        $totalPrice = 0;
        foreach ($validatedData['products'] as $product) {
            $productModel = \App\Models\Product::find($product['id_product']); // Tìm sản phẩm
            if ($productModel) {
                $totalPrice += $productModel->price * $product['quantity']; // Tổng giá = giá sản phẩm * số lượng
            }
        }

        // **Xử lý ảnh nếu có**
        if ($request->hasFile('image_combos')) {
            $destinationPath = public_path('combos');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $imageName = time() . '_' . $request->file('image_combos')->getClientOriginalName();
            $request->file('image_combos')->move($destinationPath, $imageName);
            $validatedData['image_combos'] = 'combos/' . $imageName;
        }

        // **Tạo combo**
        $combo = self::create([
            'name' => $validatedData['name'],
            'price' => $totalPrice, // Tổng giá combo
            'description' => $validatedData['description'] ?? null,
            'image_combos' => $validatedData['image_combos'] ?? null
        ]);

        // **Liên kết sản phẩm với combo**
        foreach ($validatedData['products'] as $product) {
            \App\Models\ProductCombo::create([
                'id_combo' => $combo->id_combo,
                'id_product' => $product['id_product'],
                'quantity' => $product['quantity']
            ]);
        }

        return $combo; // Trả về combo vừa tạo
    }



    // Lấy combo theo ID
    public static function getComboById($id)
    {
        // Lấy combo kèm sản phẩm
        return self::with(['products' => function ($query) {
            $query->select('products.id_product', 'products.price', 'product_combos.quantity');
        }])->find($id); // Trả về đối tượng Eloquent thay vì mảng
    }


    // Cập nhật combo
    public static function updateCombo($id, $request)
    {
        $combo = self::find($id);
        if (!$combo) {
            return null;
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string|max:1000',
            'image_combos' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image_combos')) {
            $destinationPath = public_path('combos');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $imageCombos = time() . '_' . $request->file('image_combos')->getClientOriginalName();
            $request->file('image_combos')->move($destinationPath, $imageCombos);
            $validatedData['image_combos'] = 'combos/' . $imageCombos;

            if ($combo->image_combos && file_exists(public_path($combo->image_combos))) {
                unlink(public_path($combo->image_combos));
            }
        }

        $combo->update($validatedData);
        return $combo;
    }

    // Xóa combo
    public function deleteCombo()
    {
        // Xóa các liên kết giữa combo và sản phẩm trong bảng product_combos
        $this->products()->detach();

        // Xóa ảnh combo nếu có
        if ($this->image_combos && file_exists(public_path($this->image_combos))) {
            unlink(public_path($this->image_combos));
        }

        // Xóa combo
        return $this->delete();
    }
}
