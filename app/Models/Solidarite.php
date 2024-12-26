<?php

// app/Models/Solidarite.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solidarite extends Model
{
    protected $fillable = ['user_id', 'amount', 'status', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
