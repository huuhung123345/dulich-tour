<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourImage extends Model
{
    protected $table = 'tour_images';
    protected $primaryKey = 'image_id';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['is_primary' => 'boolean'];
}
