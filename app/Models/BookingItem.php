<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    public $table = 'booking_item';
    public $timestamps = true;
    protected $fillable =[
        'booking_id',
        'item_id',
        'amount',
        'return_status',
        'date_return',
        'staff_id'
    ];
}
