<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public $table = 'Item';
    public $timestamps = True;
    protected $fillable =[
        'name',
        'amount',
        'active',
        'is_not_return',
        'store_id'
    ];

}
