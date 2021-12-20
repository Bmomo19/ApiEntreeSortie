<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeInfo extends Model
{
    public function infoComplementaire()
    {
        return $this->hasMany('App\InfoComplementaire');
    }
}
