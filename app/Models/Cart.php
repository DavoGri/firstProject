<?php

namespace App\Models;

use App\Policies\CartPolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

 protected $table='carts';

 protected $fillable=['user_id','product_id','total_items','total_price'];

    protected $policies = [
        Cart::class => CartPolicy::class,
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
