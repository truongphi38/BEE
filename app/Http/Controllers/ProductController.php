<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    function detail(){
        return view('detail');
    }
    public function index()
    {
       
        $products = Product::all();
        $categories = Category::all();
        return view('admin.index2', compact('products','categories'));
    }

    
    function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'img' => 'nullable|image|max:2048'
    ]);

    $imagePath = null;
    if ($request->hasFile('img')) {
        $file = $request->file('img');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('img'), $filename);
        $imagePath = 'img/' . $filename;
    }

    $product = Product::create([
        'name' => $request->name,
        'price' => $request->price,
        'discount_price' => $request->discount_price ?? 0,
        'description' => $request->description ?? '',
        'category_id' => $request->category_id,
        'img' => $imagePath,
    ]); 
    if ($product) {
        if (isset($request->stock) && is_array($request->stock)) {
            foreach ($request->size as $index => $size) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'stock_quantity' => $request->stock[$index] ?? 0, // Đổi từ stock_quantity thành stock
                    'price' => $request->price
                ]);
            }
        }
    }
    

    return redirect()->back()->with('success', 'Product created successfully.');
}


    public function create(){
        $categories = Category::all();
        return view('admin.create', compact('categories'));
    }

    function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
