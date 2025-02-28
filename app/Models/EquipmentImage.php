<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentImage extends Model
{
    use HasFactory;

    protected $table = 'Equipment_Images';
    protected $primaryKey = 'EQI_ID';
    public $timestamps = false;

    protected $fillable = [
        'Equip_ID',
        'EQI_Image',
        'EQI_Discription',
        'Is_Deleted'
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'Equip_ID', 'Equip_ID');
    }
}
