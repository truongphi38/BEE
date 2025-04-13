<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('user')->latest()->get();
        return response()->json($blogs);
    }

    

    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        return response()->json($blog);
    }

   
    
}
