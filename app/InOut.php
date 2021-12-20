<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InOut extends Model
{

    protected $with = ['visiteur', 'user'];
    


    public function visiteur()
    {
        return $this->belongsTo('App\Visiteur');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
