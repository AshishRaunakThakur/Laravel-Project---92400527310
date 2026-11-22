<?php

namespace App\Http\Controllers;

use App\Models\CleaningCategory;
use App\Models\CleaningPriority;
use App\Models\CleaningRecord;
use App\Models\CleaningRequest;
use App\Models\CampusLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CleaningRequestController extends Controller
{
    public function create()
    {
        return view('requests.create', ['locations' => CampusLocation::where('is_active',true)->orderBy('name')->get(), 'categories' => CleaningCategory::where('is_active',true)->orderBy('name')->get(), 'priorities'=>CleaningPriority::where('is_active',true)->orderBy('sort_order')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_id' => ['required','integer',Rule::exists('campus_locations','id')->where('is_active',true)],
            'category_id' => ['required','integer',Rule::exists('cleaning_categories','id')->where('is_active',true)],
            'description' => ['required','string','max:2000'],
            'suggested_priority' => ['nullable',Rule::exists('cleaning_priorities','name')->where('is_active',true)],
        ]);
        $data['suggested_priority_id']=!empty($data['suggested_priority']) ? CleaningPriority::where('name',$data['suggested_priority'])->value('id') : null;
        $request->user()->cleaningRequests()->create($data + ['status' => 'Submitted', 'submitted_at' => now()]);
        return redirect()->route('dashboard')->with('success','Your request was submitted for administrator review.');
    }

    public function show(CleaningRequest $cleaningRequest, Request $request)
    {
        abort_unless($request->user()->isAdmin() || $cleaningRequest->requester_id === $request->user()->id, 403);
        return view('requests.show', ['cleaningRequest' => $cleaningRequest->load(['requester','campusLocation','cleaningCategory','suggestedPriority','task.assignedStaff']), 'staff'=>User::where('role','staff')->where('is_active',true)->orderBy('name')->get(), 'priorities'=>CleaningPriority::where('is_active',true)->orderBy('sort_order')->get()]);
    }

    public function adminIndex(Request $request)
    {
        $request->validate(['status'=>['nullable','in:Submitted,Assigned,In Progress,Completed,Rejected'],'search'=>['nullable','string','max:150']]);
        $requests = CleaningRequest::with(['requester','campusLocation','cleaningCategory','task'])
            ->when($request->filled('status'), fn ($q) => $q->where('status',$request->input('status')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->string('search')->toString();
                $q->where(fn ($sub) => $sub->whereHas('requester',fn ($u) => $u->where('name','like',"%{$term}%"))
                    ->orWhereHas('campusLocation',fn ($l) => $l->where('name','like',"%{$term}%")));
            })->latest('submitted_at')->paginate(15)->withQueryString();
        return view('requests.admin-index', compact('requests'));
    }

    public function approve(Request $request, CleaningRequest $cleaningRequest)
    {
        $data = $request->validate([
            'assigned_staff_id' => ['required','integer',Rule::exists('users','id')->where(fn ($q) => $q->where('role','staff')->where('is_active',true))],
            'priority' => ['required',Rule::exists('cleaning_priorities','name')->where('is_active',true)],
            'scheduled_date' => ['required','date','after_or_equal:today'],
            'admin_remarks' => ['nullable','string','max:1000'],
        ]);
        DB::transaction(function () use ($cleaningRequest,$data) {
            $record = CleaningRequest::whereKey($cleaningRequest->id)->lockForUpdate()->with(['campusLocation','cleaningCategory'])->firstOrFail();
            abort_unless($record->status === 'Submitted', 422, 'Only submitted requests can be approved.');
            if (!$record->task()->exists()) {
                $staff = User::findOrFail($data['assigned_staff_id']);
                $priority=CleaningPriority::where('name',$data['priority'])->firstOrFail();
                $record->task()->create([
                    'location_id' => $record->location_id, 'category_id' => $record->category_id,
                    'priority_id'=>$priority->id,
                    'location' => $record->campusLocation->name, 'category' => $record->cleaningCategory->name,
                    'description' => $record->description, 'priority' => $data['priority'],
                    'assigned_staff_id' => $staff->id, 'assigned_staff' => $staff->name,
                    'status' => 'Pending', 'scheduled_date' => $data['scheduled_date'],
                ]);
            }
            $record->update(['status' => 'Assigned', 'admin_remarks' => $data['admin_remarks'] ?? null]);
        });
        return redirect()->route('admin.requests.index')->with('success','Request approved and assigned as a cleaning task.');
    }

    public function reject(Request $request, CleaningRequest $cleaningRequest)
    {
        $data = $request->validate(['admin_remarks' => ['required','string','max:1000']]);
        DB::transaction(function () use ($cleaningRequest,$data) {
            $locked=CleaningRequest::whereKey($cleaningRequest->id)->lockForUpdate()->firstOrFail();
            abort_unless($locked->status === 'Submitted', 422, 'Only submitted requests can be rejected.');
            $locked->update(['status'=>'Rejected','admin_remarks'=>$data['admin_remarks']]);
        });
        return redirect()->route('admin.requests.index')->with('success','Request rejected and the requester was notified in their dashboard.');
    }
}
