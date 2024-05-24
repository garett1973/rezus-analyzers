<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseToMednet extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_kodas',
        'barkodas',
        'tyrimo_kodas',
        'tyr_subkodas',
        'data',
        'laikas',
        'rezultatas',
        'rezultato_textas',
        'lowerLimit',
        'upperLimit',
        'unit',
        'clientCode1',
        'clientCode2',
        'patient',
        'doctor',
        'foxus',
        'position',
        'status',
        'testOrderType',
        'notes',
        'birthday',
        'deviation',
    ];
}
