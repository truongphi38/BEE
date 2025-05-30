<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $table = 'types';
    protected $fillable = ['name','description','img'];

    public function products(){
        return $this->hasMany(Product::class,'type_id');
    }

    
}
