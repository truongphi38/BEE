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
        ]);
        Type::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return redirect()->back()->with('success', 'Type created successfully.');
    }

    public function create(){
        return view('admin.type.create');
    }
}
