<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Class_Room_Bookings extends Model
{
    use HasFactory;

    protected $table = 'Class_Room_Bookings';
    protected $primaryKey = 'CRB_ID';
    public $timestamps = false;

    protected $fillable = [
        'Room_ID',
        'BookReqest_ID',
        'CRB_Discription',
        'Is_Deleted'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'Room_ID', 'Room_ID');
    }

    public function bookRequest()
    {
        return $this->belongsTo(BookingRequest::class, 'BookReqest_ID', 'BookReqest_ID');
    }
}
