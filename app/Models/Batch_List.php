<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch_List extends Model
{
    use HasFactory;

    protected $table = 'Batch_List';
    protected $primaryKey = 'Batch_List_ID';
    public $timestamps = false;

    protected $fillable = [
        'Batch_ID',
        'User_ID',
        'Branch_ID',
        'Status',
        'Is_Deleted'
    ];

    public function batch()
    {
        return $this->belongsTo(Batches::class, 'Batch_ID', 'Batch_ID');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'User_ID', 'User_ID');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'Branch_ID', 'Branch_ID');
    }
}
