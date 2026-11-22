<?php

namespace App\Http\Controllers;

use App\Models\CleaningRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffTaskController extends Controller
{
    public function show(Request $request, CleaningRecord $record)
    {
        abort_unless($record->assigned_staff_id === $request->user()->id, 403);
        return view('staff.tasks.show',['record'=>$record->load(['campusLocation','cleaningCategory','cleaningRequest'])]);
    }

    public function update(Request $request, CleaningRecord $record)
    {
        abort_unless($record->assigned_staff_id === $request->user()->id, 403);
        $data=$request->validate([
            'status'=>['required','in:In Progress,Completed'],
            'completion_remarks'=>['required_if:status,Completed','nullable','string','max:2000'],
        ]);
        DB::transaction(function () use ($record,$data) {
            $locked=CleaningRecord::whereKey($record->id)->lockForUpdate()->firstOrFail();
            abort_unless($locked->assigned_staff_id === auth()->id(),403);
            $allowed=['Pending'=>['In Progress'],'In Progress'=>['Completed'],'Completed'=>[]];
            abort_unless(in_array($data['status'],$allowed[$locked->status] ?? [],true),422,'Tasks can only move from Pending to In Progress to Completed.');
            $locked->update(['status'=>$data['status'],'completion_remarks'=>$data['completion_remarks'] ?? null,'completed_at'=>$data['status']==='Completed' ? now() : null]);
            if ($locked->cleaningRequest()->exists()) {
                $locked->cleaningRequest()->update(['status'=>$data['status']==='Completed' ? 'Completed' : 'In Progress']);
            }
        });
        return back()->with('success','Task progress updated.');
    }
}
