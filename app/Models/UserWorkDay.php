<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWorkDay extends Model
{
    use HasFactory;

    protected $table = 'User_Work_Days';
    protected $primaryKey = 'UWD_ID';
    public $timestamps = false;

    protected $fillable = [
        'User_ID',
        'Work_Mode_ID',
        'Day_Of_Week',
        'Work_Start_Time',
        'Work_End_Time',
        'Status',
        'Is_Deleted',
    ];

    protected $casts = [
        'Is_Deleted' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'User_ID', 'User_ID');
    }

    public function workMode()
    {
        return $this->belongsTo(WorkMode::class, 'Work_Mode_ID', 'Work_Mode_ID');
    }
}
