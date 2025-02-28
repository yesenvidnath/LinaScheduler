<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    use HasFactory;

    protected $table = 'flows';

    protected $primaryKey = 'Fl_ID';

    public $timestamps = false;

    protected $fillable = [
        'Branch_ID',
        'FL_Name',
        'FL_Discription',
        'Is_Deleted'
    ];

    protected $casts = [
        'Is_Deleted' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'Branch_ID', 'Branch_ID');
    }
}
