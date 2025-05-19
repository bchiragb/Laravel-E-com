<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    public function parentcat(){
        return $this->belongsTo(self::class, 'parent');
    }

    public function childrencat() {
        return $this->hasMany(static::class, 'parent');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
