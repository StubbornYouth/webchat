<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Count extends Model
{
    use HasFactory;

    protected $table = 'counts';

    public static $ROOMLIST = [
        1 => 'room1',
        2 => 'room2'
    ];
    public $timestamps = false;
}
