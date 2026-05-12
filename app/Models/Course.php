<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'Courses';
    protected $primaryKey = 'Course_ID';
    public $timestamps = false;

    protected $fillable = [
        'Course_Name',
        'Course_Discription',
        'Status',
        'Is_Deleted'
    ];
}
