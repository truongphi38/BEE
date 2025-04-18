<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;


class TypeController extends Controller
{
    public function index(){
        $types = Type::all();
        return view('admin.type.index', compact('types'));
    }
    
    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:types,name|max:255',
            'description' => 'nullable|string|max:1000',
            'img' => 'nullable|image|max:2048',
        ]);
        $imagePath = null;
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $filename);
            $imagePath = 'img/' . $filename;
        }
        Type::create([
            'name' => $request->name,
            'description' => $request->description,
            'img' => $imagePath,
        ]);
        return redirect()->back()->with('success', 'Type created successfully.');
    }

    
    public function create(){
        return view('admin.type.create');
    }

    public function edit($id)
    {
        $type = Type::find($id);

        if (!$type) {
            return redirect()->route('admin.type.index')->with('error', 'Không tìm thấy loại!');
        }

        return view('admin.type.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'img' => 'nullable|image|max:2048',
        ]);

        $type = Type::findOrFail($id);
        $type->name = $request->name;
        $type->description = $request->description;
        if ($request->hasFile('img')) {
            // Xóa ảnh cũ nếu có
            if ($type->img && file_exists(public_path($type->img))) {
                unlink(public_path($type->img));
            }
            // Lưu ảnh mới vào public/img/
            $imageName = time() . '.' . $request->file('img')->getClientOriginalExtension();
            $request->file('img')->move(public_path('img'), $imageName);
            $type->img = 'img/' . $imageName; // Lưu đường dẫn ảnh
        }
        $type->save();
        return redirect()->route('types.index')->with('success', 'Chỉnh Sửa Thành Công!');
    }

    public function destroy($id)
    {
        $type = Type::find($id);

        if (!$type) {
            return redirect()->back()->with('error', 'Không tìm thấy loại.');
        }

        $type->delete();
        return redirect()->back()->with('success', 'Đã Xoá.');
    }
}
