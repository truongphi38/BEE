<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // public function products(){
    //     return view('products');
    // }
    function detail(){
        return view('detail');
    }
    public function index()
    {
       // $products = Product::orderBy('created_at', 'desc')->paginate(10);
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

            //$imagePath = null;
            if ($request->hasFile('img')) {
        $imagePath = $request->file('img')->store('img', 'public');
        //$imagePath = 'storage/' . $imagePath; // Thêm đường dẫn storage
    }


        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price ?? 0,
            'description' => $request->description ?? '',
            'category_id' => $request->category_id,
            'img' => $imagePath,
        ]);
        //return redirect()->route('products.index')->with('success', 'Product created successfully.');
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
