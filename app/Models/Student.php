<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'Student_ID';

    protected $fillable = [
        'User_ID',
        'Course_List_ID',
        'Is_Deleted'
    ];

    protected $casts = [
        'Is_Deleted' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'User_ID');
    }

    public function courseList()
    {
        return $this->belongsTo(CourseList::class, 'Course_List_ID');
    }
}
