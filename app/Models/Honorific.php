<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Honorific extends Model
{
    use HasFactory;

    protected $table = 'Honorifics';
    protected $primaryKey = 'Honorifics_ID';
    public $timestamps = false;

    protected $fillable = [
        'Honorific',
        'Is_Deleted'
    ];
}
