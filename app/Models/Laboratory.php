<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    use HasFactory;

    protected $primaryKey = 'Lab_ID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Room_ID',
        'Lab_Type_ID',
        'Lab_Number',
        'Lab_Discription',
        'Is_Deleted'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'Room_ID');
    }

    public function laboratoryType()
    {
        return $this->belongsTo(LaboratoryType::class, 'Lab_Type_ID');
    }
}
