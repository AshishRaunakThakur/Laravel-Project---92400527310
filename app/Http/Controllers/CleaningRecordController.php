<?php

namespace App\Http\Controllers;

use App\Models\CampusLocation;
use App\Models\CleaningCategory;
use App\Models\CleaningRecord;
use App\Models\CleaningPriority;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CleaningRecordController extends Controller
{
    public function index(Request $request)
    {
        $records = $this->filteredQuery($request)->with(['campusLocation','cleaningCategory','assignedStaff'])
            ->orderByDesc('scheduled_date')->paginate(15)->withQueryString();
        return view('records.index',['records'=>$records,'locations'=>CampusLocation::orderBy('name')->get(),'categories'=>CleaningCategory::orderBy('name')->get(),'staff'=>User::where('role','staff')->orderBy('name')->get(),'priorities'=>CleaningPriority::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('records.create', $this->formOptions());
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $record = CleaningRecord::create($this->normalizedData($data));
        return redirect()->route('admin.records.show', $record)->with('success','Cleaning task created successfully.');
    }

    public function show(CleaningRecord $record)
    {
        $record->load(['campusLocation','cleaningCategory','assignedStaff','cleaningRequest.requester']);
        return view('records.show', compact('record'));
    }

    public function edit(CleaningRecord $record)
    {
        return view('records.edit', ['record'=>$record->load(['campusLocation','cleaningCategory','assignedStaff'])] + $this->formOptions());
    }

    public function update(Request $request, CleaningRecord $record)
    {
        $data = $this->validated($request, $record);
        DB::transaction(function () use ($record,$data) {
            $record->update($this->normalizedData($data));
            if($record->cleaningRequest()->exists()) {
                $statusMap=['Pending'=>'Assigned','In Progress'=>'In Progress','Completed'=>'Completed'];
                $record->cleaningRequest()->update(['status'=>$statusMap[$record->status]]);
            }
        });
        return redirect()->route('admin.records.show',$record)->with('success','Cleaning task updated successfully.');
    }

    public function destroy(CleaningRecord $record)
    {
        if ($record->cleaningRequest()->exists()) {
            return back()->withErrors(['record'=>'This task came from a campus request and cannot be deleted. Keep its history and mark it completed instead.']);
        }
        $record->delete();
        return redirect()->route('admin.records.index')->with('success','Cleaning task deleted.');
    }

    private function filteredQuery(Request $request)
    {
        $request->validate([
            'search'=>['nullable','string','max:150'],
            'status'=>['nullable','in:Pending,In Progress,Completed'],
            'priority'=>['nullable',Rule::exists('cleaning_priorities','name')],
            'location_id'=>['nullable','integer','exists:campus_locations,id'],
            'category_id'=>['nullable','integer','exists:cleaning_categories,id'],
            'assigned_staff_id'=>['nullable','integer',Rule::exists('users','id')->where('role','staff')],
            'date_from'=>['nullable','date'],
            'date_to'=>['nullable','date',...($request->filled('date_from')?['after_or_equal:date_from']:[])],
        ]);
        return CleaningRecord::query()
            ->when($request->filled('search'),function($q) use ($request) {
                $term=$request->string('search')->toString();
                $q->where(fn($sub)=>$sub->where('location','like',"%{$term}%")->orWhere('assigned_staff','like',"%{$term}%")
                    ->orWhere('category','like',"%{$term}%")->orWhereHas('assignedStaff',fn($s)=>$s->where('name','like',"%{$term}%"))
                    ->orWhereHas('campusLocation',fn($l)=>$l->where('name','like',"%{$term}%")));
            })
            ->when($request->filled('status'),fn($q)=>$q->where('status',$request->input('status')))
            ->when($request->filled('priority'),fn($q)=>$q->where('priority',$request->input('priority')))
            ->when($request->filled('location_id'),fn($q)=>$q->where('location_id',$request->input('location_id')))
            ->when($request->filled('category_id'),fn($q)=>$q->where('category_id',$request->input('category_id')))
            ->when($request->filled('assigned_staff_id'),fn($q)=>$q->where('assigned_staff_id',$request->input('assigned_staff_id')))
            ->when($request->filled('date_from'),fn($q)=>$q->whereDate('scheduled_date','>=',$request->input('date_from')))
            ->when($request->filled('date_to'),fn($q)=>$q->whereDate('scheduled_date','<=',$request->input('date_to')))
            ->when($request->filled('date_from'),fn($q)=>$q->whereDate('scheduled_date','>=',$request->input('date_from')))
            ->when($request->filled('date_to'),fn($q)=>$q->whereDate('scheduled_date','<=',$request->input('date_to')));
    }

    private function formOptions(): array
    {
        return [
            'locations'=>CampusLocation::where('is_active',true)->orderBy('name')->get(),
            'categories'=>CleaningCategory::where('is_active',true)->orderBy('name')->get(),
            'priorities'=>CleaningPriority::where('is_active',true)->orderBy('sort_order')->get(),
            'staff'=>User::where('role','staff')->where('is_active',true)->orderBy('name')->get(),
        ];
    }

    private function validated(Request $request, ?CleaningRecord $record = null): array
    {
        return $request->validate([
            'location_id'=>['required','integer',Rule::exists('campus_locations','id')->where('is_active',true)],
            'category_id'=>['required','integer',Rule::exists('cleaning_categories','id')->where('is_active',true)],
            'description'=>['required','string','max:2000'],
            'priority'=>['required',Rule::exists('cleaning_priorities','name')->where('is_active',true)],
            'assigned_staff_id'=>['nullable','integer',Rule::exists('users','id')->where(fn($q)=>$q->where('role','staff')->where('is_active',true))],
            'status'=>['required','in:Pending,In Progress,Completed'],
            'scheduled_date'=>['required','date',...($record ? [] : ['after_or_equal:today'])],
            'completion_remarks'=>['nullable','string','max:2000'],
        ]);
    }

    private function normalizedData(array $data): array
    {
        $location=CampusLocation::findOrFail($data['location_id']);
        $category=CleaningCategory::findOrFail($data['category_id']);
        $priority=CleaningPriority::where('name',$data['priority'])->firstOrFail();
        $staff=isset($data['assigned_staff_id']) ? User::findOrFail($data['assigned_staff_id']) : null;
        return $data + [
            'location'=>$location->name,
            'category'=>$category->name,
            'priority_id'=>$priority->id,
            'assigned_staff'=>$staff?->name ?? 'Unassigned',
            'assigned_staff_id'=>$staff?->id,
            'completed_at'=>($data['status'] ?? null)==='Completed' ? now() : null,
        ];
    }
}
