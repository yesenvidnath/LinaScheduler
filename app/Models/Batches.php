<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batches extends Model
{
    use HasFactory;

    protected $table = 'Batches';
    protected $primaryKey = 'Batch_ID';
    public $timestamps = false;

    protected $fillable = [
        'Batch_Name',
        'Batch_Student_Count',
        'Batch_Discription',
        'Status',
        'Is_Deleted'
    ];
}
