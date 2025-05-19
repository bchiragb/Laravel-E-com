<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function brandnm(){
        return $this->hasOne(Brand::class, 'id', 'brand');
    }

    public function catnm(){
        return $this->hasOne(ProductCategory::class, 'id', 'category');
        //return $this->belongsTo(ProductCategory::class, 'id', 'category');
    }

    public function reviews(){
        return $this->hasMany(ProductReview::class);
    }
    
    public function variantjoin(){
        return $this->hasmany(ProductVariant::class, 'product_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
