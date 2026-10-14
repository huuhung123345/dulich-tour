<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotions';
    protected $primaryKey = 'promotion_id';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];
}
