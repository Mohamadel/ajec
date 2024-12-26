<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Définir la table associée si ce n'est pas "settings"
    protected $table = 'settings';

    // Définir les champs pouvant être remplis
    protected $fillable = ['key_name', 'value'];
    
}
