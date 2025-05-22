<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'hex_code']; // hex_code: ví dụ "#FF0000"

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
