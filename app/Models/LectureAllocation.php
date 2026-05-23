<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureAllocation extends Model
{
    use HasFactory;

    protected $table = 'Lecture_Allocations';
    protected $primaryKey = 'LA_ID';
    public $timestamps = false;

    protected $fillable = [
        'Lecturer_User_ID',
        'Batch_ID',
        'Course_ID',
        'Cls_ID',
        'Allocation_Date',
        'Day_Of_Week',
        'Session_Start_Time',
        'Session_End_Time',
        'Session_Type',
        'Is_Cancelled',
        'Is_Additional_Working_Situation',
        'Lecturer_Comment',
        'Coordinator_Comment',
        'Is_Deleted',
    ];

    protected $casts = [
        'Is_Cancelled' => 'boolean',
        'Is_Additional_Working_Situation' => 'boolean',
        'Is_Deleted' => 'boolean',
    ];

    public function lecturer()
    {
        return $this->belongsTo(Users::class, 'Lecturer_User_ID', 'User_ID');
    }

    public function batch()
    {
        return $this->belongsTo(Batches::class, 'Batch_ID', 'Batch_ID');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'Course_ID', 'Course_ID');
    }

    public function classRoom()
    {
        return $this->belongsTo(RoomClass::class, 'Cls_ID', 'Cls_ID');
    }
}
