<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    public $table = 'booking';
    public $timestamps = True;
    protected $fillable =[
        'user_id',
        'staff_id',
        'note_user',
        'note_owner',
        'date_start',
        'date_end',
        'status',
        'date_verify'
    ];
}
