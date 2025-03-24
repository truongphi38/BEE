<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'img',
        'description',
        'price',
        'discount_price',
        'status_id',
        'category_id',
        'type_id',
        'created_at',
        'updated_at'
    ];

    // Quan hệ với bảng types
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function product_variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'product_id');
    }
}
