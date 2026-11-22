<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleaningPriority extends Model
{
    protected $fillable=['name','description','sort_order','is_active'];
    protected $casts=['is_active'=>'boolean'];
    public function tasks() { return $this->hasMany(CleaningRecord::class,'priority_id'); }
    public function requests() { return $this->hasMany(CleaningRequest::class,'suggested_priority_id'); }
}
