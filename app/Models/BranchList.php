<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchList extends Model
{
    use HasFactory;

    protected $table = 'Branch_List';
    protected $primaryKey = 'Branch_List_ID';
    public $timestamps = false;

    protected $fillable = [
        'Branch_ID',
        'User_ID',
        'Is_Deleted'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'Branch_ID', 'Branch_ID');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'User_ID', 'User_ID');
    }
}
