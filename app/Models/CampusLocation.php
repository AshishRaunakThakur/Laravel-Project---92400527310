<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusLocation extends Model
{
    protected $fillable = ['name', 'building', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function tasks() { return $this->hasMany(CleaningRecord::class, 'location_id'); }
    public function requests() { return $this->hasMany(CleaningRequest::class, 'location_id'); }
}
