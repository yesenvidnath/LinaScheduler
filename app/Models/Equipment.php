<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'Equipment';
    protected $primaryKey = 'Equip_ID';
    public $timestamps = false;

    protected $fillable = [
        'Equip_Type_ID',
        'Equip_Discrption',
        'Equip_Userbility_Status',
        'Is_Booked',
        'Is_Deleted'
    ];

    protected $casts = [
        'Is_Deleted' => 'boolean',
    ];

    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class, 'Equip_Type_ID', 'Equip_Type_ID');
    }
}
