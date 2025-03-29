<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomImageList extends Model
{
    use HasFactory;

    protected $table = 'RoomImageList';
    protected $primaryKey = 'RIL_ID';
    public $timestamps = false;

    protected $fillable = [
        'Room_ID',
        'RIL_Image',
        'RIL_Discription',
        'Is_Deleted'
    ];

    protected $casts = [
        'Is_Deleted' => 'boolean',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'Room_ID', 'Room_ID');
    }
}

