<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'order_details'; 
    protected $fillable = ['order_id', 'productvariant_id', 'quantity', 'total_price'];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'productvariant_id');
    }
    

}
