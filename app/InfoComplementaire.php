<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoComplementaire extends Model
{
    protected $with = ['typeInfo'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function visiteur()
    {
        return $this->belongsTo('App\Visiteur');
    }

    public function typeInfo()
    {
        return $this->belongsTo('App\Typeinfo');
    }
}
