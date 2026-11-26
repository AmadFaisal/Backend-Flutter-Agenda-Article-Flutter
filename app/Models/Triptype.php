<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripType extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk memastikan nama tabelnya benar
    protected $table = 'trip_types';

    protected $fillable = ['user_id', 'name'];

    public function holidayPlans()
    {
        return $this->hasMany(HolidayPlans::class);
    }
}
