<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CleaningRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'location', 'category', 'description', 'priority', 'assigned_staff', 'status', 'scheduled_date',
        'location_id', 'category_id', 'priority_id', 'assigned_staff_id', 'request_id', 'completion_remarks', 'completed_at',
    ];

    protected $casts = ['scheduled_date' => 'date', 'completed_at' => 'datetime'];

    public function campusLocation() { return $this->belongsTo(CampusLocation::class, 'location_id'); }
    public function cleaningCategory() { return $this->belongsTo(CleaningCategory::class, 'category_id'); }
    public function priorityCatalog() { return $this->belongsTo(CleaningPriority::class, 'priority_id'); }
    public function assignedStaff() { return $this->belongsTo(User::class, 'assigned_staff_id'); }
    public function cleaningRequest() { return $this->belongsTo(CleaningRequest::class, 'request_id'); }
}
