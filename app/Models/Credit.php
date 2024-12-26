<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'user_id', 'requested_amount', 'approved_amount', 'amount_paid',
        'interest_rate', 'status', 'payment_status', 
        'date_borrowed', 'date_due', 'approved_date', 'rejected_date',
    ];
    
    protected $casts = [
        'date_borrowed' => 'datetime',
        'date_due' => 'datetime',
        'approved_date' => 'datetime',
        'rejected_date' => 'datetime',
    ];    
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
