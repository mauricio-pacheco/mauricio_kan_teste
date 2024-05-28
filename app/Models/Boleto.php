<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boleto extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'government_id',
        'email',
        'debt_amount',
        'due_date',
        'debt_id',
    ];

    protected $casts = [
        'debt_amount' => 'decimal:2',
        'due_date' => 'date',
    ];
}
