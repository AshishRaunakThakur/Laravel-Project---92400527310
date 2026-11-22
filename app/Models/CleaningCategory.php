<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleaningCategory extends Model
{
    protected $fillable = ['name', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function tasks() { return $this->hasMany(CleaningRecord::class, 'category_id'); }
    public function requests() { return $this->hasMany(CleaningRequest::class, 'category_id'); }
}
