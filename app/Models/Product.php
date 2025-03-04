<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'img', 'description', 'price', 'discount_price',
        'status_id', 'category_id', 'type_id', 'created_at', 'updated_at'
    ];

    // Quan hệ với bảng types
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}
