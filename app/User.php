<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['identifiant', 'nom', 'prenoms', 'dateNaiss', 'login', 'password', 'motif', 'photo'];

    public function inOut()
    {
        return $this->hasMany('App\InOut');
    }
    
}
