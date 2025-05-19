<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    //
    protected $fillable = ['id', 'pid', 'type', 'keyword', 'title', 'desc', 'canonical', 'created_at', 'updated_at'];

    protected $guarded = ['id'];
}
