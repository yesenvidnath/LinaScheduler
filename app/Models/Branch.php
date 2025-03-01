<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'Branches';

    protected $primaryKey = 'Branch_ID';

    public $timestamps = false;

    protected $fillable = [
        'Branch_Name',
        'Branch_Discription',
        'Is_Deleted'
    ];

    protected $casts = [
        'Is_Deleted' => 'boolean',
    ];
}
