<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'rental_id',
        'car_id',
        'condition',
        'comments',
        'total_fee',
        'returned_at',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
