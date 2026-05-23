<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkMode extends Model
{
    use HasFactory;

    protected $table = 'Work_Modes';
    protected $primaryKey = 'Work_Mode_ID';
    public $timestamps = false;

    protected $fillable = [
        'Work_Mode_Name',
        'Work_Mode_Description',
        'Is_Deleted',
    ];

    protected $casts = [
        'Is_Deleted' => 'boolean',
    ];

    public function userWorkDays()
    {
        return $this->hasMany(UserWorkDay::class, 'Work_Mode_ID', 'Work_Mode_ID');
    }
}
