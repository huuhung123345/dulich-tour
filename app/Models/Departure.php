<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departure extends Model
{
    protected $table = 'departures';
    protected $primaryKey = 'departure_id';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id', 'tour_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'departure_id', 'departure_id');
    }
}
