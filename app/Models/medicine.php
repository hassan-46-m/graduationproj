<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class medicine extends Model
{
    //
    protected $table="medicine";
    protected $fillable = [
        'MedicineName',
        'Price',
        'ImageURL',
        'Manufacturer',
        'Side_effects',
        'Uses',
        'Composition',
        ];

}
