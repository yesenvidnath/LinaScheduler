<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Users extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'users';

    protected $primaryKey = 'User_ID';

    public $timestamps = false;

    protected $fillable = [
        'UD_ID',
        'Honorifics_ID',
        'First_Name',
        'Last_Name',
        'Email',
        'User_Discrption',
        'Status',
        'password',
        'Is_Deleted',
        'login_attempts'
    ];

    protected $casts = [
        'Is_Deleted' => 'boolean',
        'Status' => 'string'
    ];

    public function userDesignation()
    {
        return $this->belongsTo(UserDesignation::class, 'UD_ID');
    }

    public function batchList()
    {
        return $this->belongsTo(BranchList::class, 'Batch_List_ID', 'Branch_List_ID');
    }

    public function honorifics()
    {
        return $this->belongsTo(Honorific::class, 'Honorifics_ID');
    }
}
