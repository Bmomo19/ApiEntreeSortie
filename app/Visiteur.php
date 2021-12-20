<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visiteur extends Model
{
    protected $with = ['typeVisiteur'];

    public function typeVisiteur()
    {
        return $this->belongsTo('App\TypeVisiteur');
    }

    public function in_out()
    {
        return $this->hasMany('App\InOut');
    }

    public function infoComplementaire()
    {
        return $this->hasMany('App\InfoComplementaire');
    }

    public function inOut()
    {
        return $this->hasMany('App\InOut');
    }
}
