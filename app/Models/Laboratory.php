<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    use HasFactory;

    protected $table = 'laboratories';
    protected $primaryKey = 'Lab_ID';
    public $timestamps = false;

    protected $fillable = [
        'Room_ID',
        'Lab_Type_ID',
        'Lab_Number',
        'Lab_Equipment_Count',
        'Lab_Discription',
        'Is_Deleted'
    ];

    protected $casts = [
        'Is_Deleted' => 'boolean',
        'Lab_Equipment_Count' => 'integer',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'Room_ID', 'Room_ID');
    }

    public function laboratoryType()
    {
        return $this->belongsTo(LaboratoryType::class, 'Lab_Type_ID', 'Lab_Type_ID');
    }
}
