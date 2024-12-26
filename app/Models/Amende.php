<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amende extends Model
{
    protected $fillable = ['user_id', 'amount', 'reason', 'status', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}


