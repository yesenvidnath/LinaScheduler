<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryType extends Model
{
    use HasFactory;

    protected $table = 'laboratory_types';
    protected $primaryKey = 'Lab_Type_ID';
    public $timestamps = false;

    protected $fillable = [
        'Lab_Type',
        'Lab_Type_Discription',
        'Is_Deleted'
    ];

    protected $casts = [
        'Is_Deleted' => 'boolean',
    ];
}
