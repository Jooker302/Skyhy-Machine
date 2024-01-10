<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'staff_needed',
    ];
    public function shiftRequests()
    {
        return $this->hasMany(ShiftRequest::class);
    }
    public function acceptedRequests()
    {
        return $this->hasMany(ShiftAccepted::class);
    }
}
