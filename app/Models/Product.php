<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

   protected $table='products';
   protected $fillable=[
       'name','description','price','stock_quantity','category_id'
   ];
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class,'order_items','product_id','order_id')
                    ->withPivot('quantity','item_total')
                     ->withTimestamps();
    }
}
