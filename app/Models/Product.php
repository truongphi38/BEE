<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price', 'discount_price', 'description', 'category_id', 'type_id', 'img', 'is_hot'
    ];

    // Quan hệ với biến thể
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Quan hệ với Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Quan hệ với Type
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    // Quan hệ với wishlist (nếu có)
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
