<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    //
    protected $table="Feedback";
    protected $fillable = ['user_id', 'the_feedback'];


 public function user()
    {
        return $this->belongsTo(User::class);
    }

}
