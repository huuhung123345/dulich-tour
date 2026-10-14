<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'booking_id';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['created_at' => 'datetime'];

    public const STATUS = [
        'PENDING'   => 'Chờ thanh toán',
        'PAID'      => 'Đã thanh toán',
        'CONFIRMED' => 'Đã xác nhận',
        'COMPLETED' => 'Hoàn thành',
        'CANCELLED' => 'Đã hủy',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function departure()
    {
        return $this->belongsTo(Departure::class, 'departure_id', 'departure_id');
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class, 'booking_id', 'booking_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id', 'booking_id');
    }

    public function getStatusTextAttribute(): string
    {
        return self::STATUS[$this->status] ?? $this->status;
    }
}
