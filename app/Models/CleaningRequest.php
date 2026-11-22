<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleaningRequest extends Model
{
    protected $fillable = ['requester_id', 'location_id', 'category_id', 'description', 'suggested_priority', 'suggested_priority_id', 'status', 'admin_remarks', 'submitted_at'];
    protected $casts = ['submitted_at' => 'datetime'];
    public function requester() { return $this->belongsTo(User::class, 'requester_id'); }
    public function campusLocation() { return $this->belongsTo(CampusLocation::class, 'location_id'); }
    public function cleaningCategory() { return $this->belongsTo(CleaningCategory::class, 'category_id'); }
    public function suggestedPriority() { return $this->belongsTo(CleaningPriority::class, 'suggested_priority_id'); }
    public function task() { return $this->hasOne(CleaningRecord::class, 'request_id'); }
}
