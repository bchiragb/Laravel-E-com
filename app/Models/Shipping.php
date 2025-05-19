<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Shipping extends Model
{
    public function getCountry($idx)
    {
        return DB::table('countries')
            ->where('countries.id', $idx)
            ->value('name');  
    }

    public function getState($idx)
    {
        return DB::table('states')
            ->where('id', $idx)
            ->value('name');  
            
    }
}
