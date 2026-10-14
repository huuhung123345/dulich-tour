<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $table = 'tours';
    protected $primaryKey = 'tour_id';
    public $timestamps = false;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id', 'destination_id');
    }

    public function departures()
    {
        return $this->hasMany(Departure::class, 'tour_id', 'tour_id');
    }

    public function images()
    {
        return $this->hasMany(TourImage::class, 'tour_id', 'tour_id');
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class, 'tour_id', 'tour_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'tour_id', 'tour_id');
    }

    // Anh dai dien: neu chua co link http thi dung anh mac dinh
    public function getMainImageAttribute(): string
    {
        $img = $this->images->sortByDesc('is_primary')->first();
        if ($img && str_starts_with($img->image_url, 'http')) {
            return $img->image_url;
        }
        return 'https://placehold.co/600x400/0d6efd/ffffff?text=' . urlencode($this->tour_name);
    }
}
