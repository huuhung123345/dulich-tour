<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $primaryKey = 'contact_id';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['created_at' => 'datetime'];
}
