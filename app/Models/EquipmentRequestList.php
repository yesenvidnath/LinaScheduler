<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentRequestList extends Model
{
    use HasFactory;

    protected $table = 'EquipmentRequestList';
    protected $primaryKey = 'ERL_ID';
    public $timestamps = false;

    protected $fillable = [
        'Course_ID',
        'Equip_ID',
        'Class_Type',
        'Expected_Student_Count',
        'Is_Deleted'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'Course_ID', 'Course_ID');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'Equip_ID', 'Equip_ID');
    }
}
