<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $table = 'tour_itineraries';
    protected $primaryKey = 'itinerary_id';
    public $timestamps = false;
    protected $guarded = [];
}
