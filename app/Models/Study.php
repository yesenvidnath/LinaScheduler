<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    use HasFactory;

    protected $table = 'studies';
    protected $primaryKey = 'Study_ID';
    public $timestamps = false;

    protected $fillable = [
        'Room_ID',
        'Study_Number',
        'Study_Discription',
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
