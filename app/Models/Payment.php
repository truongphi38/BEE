<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Nếu bảng là `payment` (số ít), giữ dòng này
    protected $table = 'payments';

    // Nếu bảng là `payments`, có thể bỏ dòng trên

    protected $fillable = [
        'order_id',
        'method',
        'amount',
        'payment_date',
        'status_id',
    ];

    // Mối quan hệ ngược: mỗi payment thuộc về một đơn hàng
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
