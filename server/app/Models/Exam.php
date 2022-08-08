<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'value_type_id',
        'user_id',
        'unity'
    ];
   
  
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function valueType()
    {
        return $this->belongsTo(ValueType::class,'value_type_id');
    }
    public function plannings()
    {
        return $this->hasMany(Planning::class);
    }

}
