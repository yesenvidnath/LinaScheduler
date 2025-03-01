<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryType extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'Laboratory_Type';

    // Specify the primary key
    protected $primaryKey = 'Lab_Type_ID';

    // Specify the fillable properties
    protected $fillable = [
        'Lab_Type',
        'Lab_Type_Discription',
        'Is_Deleted'
    ];

    // Disable timestamps if not used
    public $timestamps = false;
}
