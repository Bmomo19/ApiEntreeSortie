<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeVisiteur extends Model
{
    
    public function visiteur()
    {
        return $this->hasMany('App\Visiteur');
    }
    
}