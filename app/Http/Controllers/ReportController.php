<?php

namespace App\Http\Controllers;

use App\Models\CampusLocation;
use App\Models\CleaningCategory;
use App\Models\CleaningRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query=$this->query($request);
        $records=(clone $query)->with(['campusLocation','cleaningCategory','assignedStaff'])->orderByDesc('scheduled_date')->paginate(15)->withQueryString();
        $summary=[
            'total'=>(clone $query)->count(),
            'completed'=>(clone $query)->where('status','Completed')->count(),
            'pending'=>(clone $query)->where('status','Pending')->count(),
            'overdue'=>(clone $query)->whereDate('scheduled_date','<',today())->where('status','!=','Completed')->count(),
        ];
        return view('reports.index',['records'=>$records,'summary'=>$summary,'locations'=>CampusLocation::orderBy('name')->get(),'categories'=>CleaningCategory::orderBy('name')->get(),'staff'=>User::where('role','staff')->orderBy('name')->get()]);
    }

    public function export(Request $request): StreamedResponse
    {
        $records=$this->query($request)->with(['campusLocation','cleaningCategory','assignedStaff'])->orderByDesc('scheduled_date')->get();
        $filename='campus-cleaning-report-'.now()->format('Y-m-d-His').'.csv';
        return response()->streamDownload(function () use ($records) {
            $out=fopen('php://output','w');
            fputcsv($out,['Task ID','Location','Category','Description','Priority','Assigned staff','Status','Scheduled date','Completed date','Completion remarks']);
            foreach($records as $record) {
                fputcsv($out,array_map([$this,'safeCsv'],[
                    $record->id,$record->campusLocation?->name ?? $record->location,$record->cleaningCategory?->name ?? $record->category,
                    $record->description,$record->priority,$record->assignedStaff?->name ?? $record->assigned_staff,$record->status,
                    $record->scheduled_date->format('Y-m-d'),$record->completed_at?->format('Y-m-d H:i:s'),$record->completion_remarks,
                ]));
            }
            fclose($out);
        },$filename,['Content-Type'=>'text/csv; charset=UTF-8']);
    }

    private function query(Request $request)
    {
        $request->validate([
            'date_from'=>['nullable','date'],
            'date_to'=>['nullable','date',...($request->filled('date_from')?['after_or_equal:date_from']:[])],
            'location_id'=>['nullable','integer','exists:campus_locations,id'],
            'category_id'=>['nullable','integer','exists:cleaning_categories,id'],
            'assigned_staff_id'=>['nullable','integer',Rule::exists('users','id')->where('role','staff')],
            'status'=>['nullable','in:Pending,In Progress,Completed'],
        ]);
        return CleaningRecord::query()
            ->when($request->filled('date_from'),fn($q)=>$q->whereDate('scheduled_date','>=',$request->input('date_from')))
            ->when($request->filled('date_to'),fn($q)=>$q->whereDate('scheduled_date','<=',$request->input('date_to')))
            ->when($request->filled('location_id'),fn($q)=>$q->where('location_id',$request->input('location_id')))
            ->when($request->filled('category_id'),fn($q)=>$q->where('category_id',$request->input('category_id')))
            ->when($request->filled('assigned_staff_id'),fn($q)=>$q->where('assigned_staff_id',$request->input('assigned_staff_id')))
            ->when($request->filled('status'),fn($q)=>$q->where('status',$request->input('status')));
    }

    private function safeCsv($value): string
    {
        $value=(string)($value ?? '');
        return preg_match('/^[\s\x00-\x1F]*[=+@\-]/u',$value) ? "'".$value : $value;
    }
}
