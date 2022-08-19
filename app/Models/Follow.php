<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public $table = 'follow';
    public $timestamps = false;
    protected $fillable =[
        'user_id',
        'owner_id'
    ];
}
