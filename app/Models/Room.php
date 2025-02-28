<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'Rooms';

    protected $primaryKey = 'Room_ID';

    protected $fillable = [
        'Fl_ID',
        'Room_Number',
        'Room_Discrption',
        'Room_Availability',
        'Room_Type',
        'Max_Student_Count',
        'Max_Chair_Count',
        'Max_Power_Outlets',
        'Max_Table_Count',
        'Is_WhiteBoard_Avilable',
        'Is_Projector_Avilable',
        'Is_Smart_board_Avilable',
        'Is_Deleted'
    ];

    public $timestamps = false;

    public function flow()
    {
        return $this->belongsTo(Flow::class, 'Fl_ID', 'Fl_ID');
    }
}
