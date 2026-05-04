<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'Class';
    protected $primaryKey = 'Cls_ID';
    public $timestamps = false;

    protected $fillable = [
        'Room_ID',
        'Cls_Number',
        'Cls_Discription',
        'Is_Deleted'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'Room_ID');
    }
}
