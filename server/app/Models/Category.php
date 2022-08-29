<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'user_id'
    ];
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
    public function getCreatedAtAttribute($date)
    {
        return  Carbon::parse($date)->format('Y-m-d H:i:s');
    }
      // this is the recommended way for declaring event handlers
      public static function boot() {
        parent::boot();
        self::deleting(function($user) { // before delete() method call this
             $user->exams()->each(function($photo) {
                $photo->delete(); // <-- direct deletion
             });
             // do the rest of the cleanup...
        });
    }
}
