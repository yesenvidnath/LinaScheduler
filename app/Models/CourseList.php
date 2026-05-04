<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseList extends Model
{
    use HasFactory;

    protected $primaryKey = 'Course_List_ID';

    protected $fillable = [
        'Course_ID',
        'User_ID',
        'Is_Deleted'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'Course_ID');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'User_ID');
    }
}
