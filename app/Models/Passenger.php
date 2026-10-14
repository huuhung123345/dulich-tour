<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    protected $table = 'booking_passengers';
    protected $primaryKey = 'passenger_id';
    public $timestamps = false;
    protected $guarded = [];
}
