<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;

    protected $table = 'students'; // Your table name
    
    protected $fillable = [
        'Roll', 'Name', 'Madrasha', 'MID', 'Class', 'Father', 
        'DateofBirth', 'years', 'reg_id', 'type', 'IDs'
    ];

    protected $casts = [
        'DateofBirth' => 'date',
        'years' => 'integer',
        'Roll' => 'integer',
    ];
}