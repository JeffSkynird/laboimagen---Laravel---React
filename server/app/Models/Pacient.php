<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pacient extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'dni',
        'names',
        'last_names',
        'address',
        'phone',
        'email',
        'user_id',
        'born_date',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function getCreatedAtAttribute($date)
    {
        return  Carbon::parse($date)->format('Y-m-d H:i:s');
    }
    
}
