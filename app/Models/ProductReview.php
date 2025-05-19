<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    public function productnm(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
