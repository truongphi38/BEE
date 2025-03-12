<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;

class PromotionController extends Controller
{

    public function index()
    {
        $promotions = Promotion::orderBy('created_at', 'desc')->get();
        return view('admin.promotions.index', compact('promotions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:promotions,code|max:255',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Promotion::create([
            'code' => $request->code,
            'discount_percent' => $request->discount_percent ?? 0,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        return redirect()->back()->with('success', 'Tạo Thành Công');
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function delete($id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return redirect()->back()->with('error', 'Không tìm thấy mã khuyến mãi.');
        }

        $promotion->delete();
        return redirect()->back()->with('success', 'Xoá thành công.');
    }

    public function edit($id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return redirect()->route('promotion.index')->with('error', 'Không tìm thấy mã khuyến mãi!');
        }

        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:promotions,code|max:255',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $promotion = Promotion::find($id);
        if (!$promotion) {
            return redirect()->route('promotions.index')->with('error', 'Không tìm thấy!');
        }

        $promotion->update([
            'code' => $request->code,
            'discount_percent' => $request->discount_percent ?? 0,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('promotions.index')->with('success', 'Cập nhật thành công!');
    }
}
