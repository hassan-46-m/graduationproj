<?php

namespace App\Models;

use App\Models\User;
use App\Models\medicine;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    protected $fillable = ['user_id', 'medicine_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicine()
    {
        return $this->belongsTo(medicine::class);
    }
}

