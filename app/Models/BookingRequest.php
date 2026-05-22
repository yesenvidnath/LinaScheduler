<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    use HasFactory;

    protected $table = 'BookingRequest';
    protected $primaryKey = 'BookRequest_ID';
    public $timestamps = false;

    protected $fillable = [
        'Course_ID',
        'Batch_ID',
        'User_ID',
        'ERL_ID',
        'Class_Type',
        'Expected_Student_Count',
        'Class_Start_Time',
        'Class_End_Time',
        'Status',
        'Is_Deleted'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'Course_ID', 'Course_ID');
    }

    public function batch()
    {
        return $this->belongsTo(Batches::class, 'Batch_ID', 'Batch_ID');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'User_ID', 'User_ID');
    }

    public function equipmentRequestList()
    {
        return $this->belongsTo(EquipmentRequestList::class, 'ERL_ID', 'ERL_ID');
    }

    public function roomBookings()
    {
        return $this->hasMany(Class_Room_Bookings::class, 'BookRequest_ID', 'BookRequest_ID');
    }
}
