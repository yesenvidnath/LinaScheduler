<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    use HasFactory;

    protected $table = 'EquipmentType';
    protected $primaryKey = 'Equip_Type_ID';
    public $timestamps = false;

    protected $fillable = [
        'Equip_Type',
        'Equip_Type_Discrption',
        'Is_Deleted'
    ];

    protected $casts = [
        'Is_Deleted' => 'boolean',
    ];
}
