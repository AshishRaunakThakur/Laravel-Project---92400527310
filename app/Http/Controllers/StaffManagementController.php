<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffManagementController extends Controller
{
    public function index() { return view('admin.staff.index',['staff'=>User::where('role','staff')->withCount('cleaningTasks')->orderBy('name')->paginate(15)]); }
    public function create() { return view('admin.staff.form',['member'=>null]); }
    public function store(Request $request)
    {
        $data=$request->validate(['name'=>['required','string','max:120'],'email'=>['required','email','max:190','unique:users,email'],'password'=>['required','confirmed','min:8']]);
        User::create($data+['password'=>Hash::make($data['password']),'role'=>'staff','is_active'=>true]);
        return redirect()->route('admin.staff.index')->with('success','Staff account created. Share the temporary password securely.');
    }
    public function edit(User $member) { abort_unless($member->role==='staff',404); return view('admin.staff.form',compact('member')); }
    public function update(Request $request, User $member)
    {
        abort_unless($member->role==='staff',404);
        $data=$request->validate(['name'=>['required','string','max:120'],'email'=>['required','email','max:190',Rule::unique('users','email')->ignore($member->id)],'password'=>['nullable','confirmed','min:8']]);
        if(!empty($data['password'])) $member->password=Hash::make($data['password']);
        $member->fill(['name'=>$data['name'],'email'=>$data['email']])->save();
        return redirect()->route('admin.staff.index')->with('success','Staff profile updated.');
    }
    public function toggle(User $member)
    {
        abort_unless($member->role==='staff',404);
        $member->update(['is_active'=>!$member->is_active]);
        return back()->with('success',$member->is_active ? 'Staff account activated.' : 'Staff account deactivated. Existing task history was kept.');
    }
    public function show(User $member) { abort_unless($member->role==='staff',404); return view('admin.staff.show',['member'=>$member,'tasks'=>$member->cleaningTasks()->with(['campusLocation','cleaningCategory'])->latest('scheduled_date')->paginate(10)]); }
}
