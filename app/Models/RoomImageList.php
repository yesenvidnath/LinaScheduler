<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomImageList extends Model
{
    use HasFactory;

    protected $table = 'Room_Image_List';

    protected $primaryKey = 'RIL_ID';

    protected $fillable = [
        'Room_ID',
        'Room_Image',
        'RIL_Discrption',
        'Is_Deleted'
    ];

    public $timestamps = false;

    public function room()
    {
        return $this->belongsTo(Room::class, 'Room_ID');
    }
}

