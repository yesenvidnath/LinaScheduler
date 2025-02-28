<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDesignation extends Model
{
    use HasFactory;

    protected $table = 'UserDesignations';

    protected $primaryKey = 'UD_ID';

    public $timestamps = false;

    protected $fillable = [
        'Designation',
        'Is_Deleted'
    ];

    protected $casts = [
        'Is_Deleted' => 'boolean',
    ];
}
