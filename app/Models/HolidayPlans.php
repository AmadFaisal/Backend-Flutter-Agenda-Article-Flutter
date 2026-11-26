<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HolidayPlans extends Model
{
    protected $fillable = ['user_id', 'trip_type_id', 'title', 'notes', 'image_url', 'holiday_date'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tripType()
    {
        return $this->belongsTo(Triptype::class);
    }
}
