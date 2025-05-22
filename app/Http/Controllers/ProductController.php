<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Type;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'category',
            'type',
            'wishlists',
            'productVariants.color'
        ])->withCount('wishlists')->get();

        return view('admin.index2', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $types = Type::all();
        $colors = Color::all();

        return view('admin.create', compact('categories', 'types', 'colors'));
    }

    public function store(Request $request)
    {
        // Validate thông tin sản phẩm
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'type_id' => 'required|integer|exists:types,id',
            'description' => 'nullable|string',
            'img' => 'required|image|max:2048',
    
            'variants' => 'required|array|min:1',
            'variants.*.color_id' => 'required|integer|exists:colors,id',
            'variants.*.size' => 'required|string|max:10',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.discount_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
        ]);
    
        // Xử lý upload ảnh
        $imgPath = null;
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/folderanh'), $filename);
            $imgPath = 'img/folderanh/' . $filename;
        }
    
        // Lấy giá mặc định lấy từ biến thể đầu tiên
        $firstVariant = $request->variants[0];
        $price = $firstVariant['price'];
        $discountPrice = $firstVariant['discount_price'] ?? 0;
    
        // Tạo sản phẩm mới
        $product = new Product();
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->type_id = $request->type_id;
        $product->description = $request->description;
        $product->price = $price;
        $product->discount_price = $discountPrice;
        $product->img = $imgPath;
        $product->save();
    
        // Lưu biến thể
        foreach ($request->variants as $variantData) {
            $variant = new ProductVariant();
            $variant->product_id = $product->id;
            $variant->color_id = $variantData['color_id'];
            $variant->size = $variantData['size'];
            $variant->price = $variantData['price'];
            $variant->discount_price = $variantData['discount_price'] ?? 0;
            $variant->stock_quantity = $variantData['stock_quantity'];
            $variant->save();
        }
    
        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công!');
    }
    

public function show($id)
{
    $product = Product::with([
        'productVariants' => function ($query) {
            $query->with('color')->orderBy('id'); // lấy màu đầu tiên theo thứ tự ID
        },
        'category',
        'type'
    ])->find($id);

    if (!$product) {
        abort(404, 'Sản phẩm không tồn tại');
    }

    // Lấy giá gốc từ màu đầu tiên
    $firstVariant = $product->productVariants->first();
    $basePrice = $firstVariant ? $firstVariant->price : 0;

    return view('admin.product.show', compact('product', 'basePrice'));
}


    public function getReviews($id)
    {
        $product = Product::with('reviews.user')->findOrFail($id);

        $html = "";
        foreach ($product->reviews as $review) {
            $html .= "
                <div class='border p-3 mb-3'>
                    <strong>{$review->user->name}</strong> - <span class='text-warning'>⭐ {$review->rating}/5</span>
                    <p>{$review->comment}</p>
                    <small class='text-muted'>Ngày đánh giá: {$review->created_at->format('d/m/Y H:i')}</small>
                </div>
            ";
        }

        return response($html);
    }

    public function toggleHot(Request $request, $id)
    {
        $request->validate([
            'is_hot' => 'required|boolean',
        ]);

        $product = Product::findOrFail($id);
        $product->is_hot = $request->is_hot;
        $product->save();

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $product = Product::with('productVariants')->findOrFail($id);
        $colors = Color::all();
        $categories = Category::all();
        $types = Type::all();

        return view('admin.edit_product', compact('product', 'colors', 'categories', 'types'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:types,id',
            'img' => 'nullable|image|max:2048',
            'color_id' => 'required|array|min:1',
            'stock_quantity' => 'required|array|min:1',
            'stock_quantity.*' => 'required|integer|min:0',
            'variant_price' => 'required|array|min:1',
            'variant_price.*' => 'required|numeric|min:1',
            'variant_discount_price' => 'nullable|array',
            'variant_discount_price.*' => 'nullable|numeric|min:0',
        ]);

        // Xử lý ảnh mới upload (nếu có)
        if ($request->hasFile('img')) {
            if ($product->img && File::exists(public_path($product->img))) {
                File::delete(public_path($product->img));
            }
            $file = $request->file('img');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/folderanh'), $filename);
            $product->img = 'img/folderanh/' . $filename;
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price ?? 0,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'type_id' => $request->type_id,
            'img' => $product->img,
        ]);

        // Xóa biến thể cũ và thêm biến thể mới
        $product->productVariants()->delete();

        foreach ($request->color_id as $index => $colorId) {
            $product->productVariants()->create([
                'color_id' => $colorId,
                'stock_quantity' => $request->stock_quantity[$index] ?? 0,
                'price' => $request->variant_price[$index],
                'discount_price' => $request->variant_discount_price[$index] ?? null,
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        // Xóa ảnh sản phẩm nếu có
        if ($product->img && File::exists(public_path($product->img))) {
            File::delete(public_path($product->img));
        }

        // Xóa biến thể trước khi xóa sản phẩm
        $product->productVariants()->delete();

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công.');
    }
}
