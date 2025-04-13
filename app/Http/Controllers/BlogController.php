<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required',
        'img' => 'nullable|image|max:2048',
    ]);
    // Xử lý ảnh
    $imagePath = null;
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $filename);
            $imagePath = 'img/' . $filename;
        }
    // $blog = new Blog();
    // $blog->title = $request->title;
    // $blog->content = $request->content;
    // $blog->status = $request->has('status') ? 1 : 0;
    // $blog->img = $imagePath;    
    // Gán user_id mặc định là 1 nếu không đăng nhập
    // $blog->user_id = auth()->check() ? auth()->id() : 1;
    Blog::create([
        'title' => $request->title,
        'content' => $request->content,
        'status' => $request->has('status') ? 1 : 0,
        'user_id' => auth()->check() ? auth()->id() : 1,
        'img' => $imagePath,
    ]);
    return redirect()->back()->with('success', 'Type created successfully.');

    

    // $blog->save();

    // return redirect()->route('admin.blogs.index')->with('success', 'Tạo blog thành công!');
}

    

    public function edit($id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return redirect()->route('admin.blogs.index')->with('error', 'Blog không tồn tại.');
        }

        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'img' => 'nullable|image|max:2048',
        ]);

        $blog = Blog::find($id);
        if (!$blog) {
            return redirect()->route('admin.blogs.index')->with('error', 'Blog không tồn tại.');
        }

        $data = [
            'title' => $request->title,
            'content' => $request->content,
        ];

        $imagePath = null;
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $filename);
            $imagePath = 'img/' . $filename;
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return redirect()->route('admin.blogs.index')->with('error', 'Blog không tồn tại.');
        }

        $blog->delete();
        return redirect()->route('admin.blogs.index')->with('success', 'Blog đã được xóa thành công.');
    }

    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.show', compact('blog'));
    }
}
