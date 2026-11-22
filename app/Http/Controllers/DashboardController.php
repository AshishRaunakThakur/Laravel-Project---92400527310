<?php

namespace App\Http\Controllers;

use App\Models\CleaningRecord;
use App\Models\CleaningRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->isAdmin()) {
            $taskCounts = [
                'total' => CleaningRecord::count(),
                'pending' => CleaningRecord::where('status', 'Pending')->count(),
                'inProgress' => CleaningRecord::where('status', 'In Progress')->count(),
                'completed' => CleaningRecord::where('status', 'Completed')->count(),
                'overdue' => CleaningRecord::whereDate('scheduled_date', '<', today())->whereNotIn('status', ['Completed'])->count(),
            ];
            $requestCounts = [
                'submitted' => CleaningRequest::where('status', 'Submitted')->count(),
                'assigned' => CleaningRequest::where('status', 'Assigned')->count(),
                'rejected' => CleaningRequest::where('status', 'Rejected')->count(),
            ];
            $upcoming = CleaningRecord::with(['campusLocation', 'cleaningCategory', 'assignedStaff'])
                ->whereDate('scheduled_date', '>=', today())->orderBy('scheduled_date')->take(6)->get();
            $recentTasks = CleaningRecord::with(['campusLocation','cleaningCategory','assignedStaff'])->latest('updated_at')->take(5)->get();
            $recentRequests = CleaningRequest::with(['requester', 'campusLocation', 'cleaningCategory'])->latest('submitted_at')->take(5)->get();
            return view('dashboard.admin', compact('taskCounts', 'requestCounts', 'upcoming', 'recentRequests','recentTasks'));
        }
        if ($user->isStaff()) {
            $request->validate(['status'=>['nullable','in:Pending,In Progress,Completed'],'date_from'=>['nullable','date'],'date_to'=>['nullable','date',...($request->filled('date_from')?['after_or_equal:date_from']:[])]]);
            $tasks = $user->cleaningTasks()->with(['campusLocation', 'cleaningCategory'])
                ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
                ->when($request->filled('date_from'), fn ($query) => $query->whereDate('scheduled_date','>=',$request->input('date_from')))
                ->when($request->filled('date_to'), fn ($query) => $query->whereDate('scheduled_date','<=',$request->input('date_to')))
                ->orderByRaw("CASE WHEN status = 'Completed' THEN 1 ELSE 0 END")
                ->orderBy('scheduled_date')->paginate(10)->withQueryString();
            $counts = ['total' => $user->cleaningTasks()->count(), 'pending' => $user->cleaningTasks()->where('status','Pending')->count(), 'inProgress' => $user->cleaningTasks()->where('status','In Progress')->count(), 'completed' => $user->cleaningTasks()->where('status','Completed')->count()];
            return view('dashboard.staff', compact('tasks', 'counts'));
        }
        $request->validate(['status'=>['nullable','in:Submitted,Assigned,In Progress,Completed,Rejected'],'search'=>['nullable','string','max:150']]);
        $requests = $user->cleaningRequests()->with(['campusLocation', 'cleaningCategory', 'task'])->latest('submitted_at')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $term=$request->string('search')->toString();
                $query->where(fn($sub)=>$sub->where('description','like',"%{$term}%")
                    ->orWhereHas('campusLocation',fn($l)=>$l->where('name','like',"%{$term}%"))
                    ->orWhereHas('cleaningCategory',fn($c)=>$c->where('name','like',"%{$term}%")));
            })
            ->paginate(10)->withQueryString();
        $counts = $user->cleaningRequests()->selectRaw('status, count(*) as aggregate')->groupBy('status')->pluck('aggregate','status');
        return view('dashboard.campus', compact('requests', 'counts'));
    }
}
