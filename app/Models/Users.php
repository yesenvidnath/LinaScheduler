<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $table = 'Users';

    protected $primaryKey = 'User_ID';

    public $timestamps = false;

    protected $fillable = [
        'UD_ID',
        'Honorifics_ID',
        'First_Name',
        'Last_Name',
        'User_Discrption',
        'Status',
        'password',
        'Is_Deleted'
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
