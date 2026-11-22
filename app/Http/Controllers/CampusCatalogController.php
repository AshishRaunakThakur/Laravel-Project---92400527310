<?php

namespace App\Http\Controllers;

use App\Models\CampusLocation;
use App\Models\CleaningCategory;
use App\Models\CleaningPriority;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CampusCatalogController extends Controller
{
    public function locations() { return view('admin.catalog.index',['items'=>CampusLocation::withCount(['tasks','requests'])->orderBy('name')->paginate(15),'kind'=>'locations']); }
    public function categories() { return view('admin.catalog.index',['items'=>CleaningCategory::withCount(['tasks','requests'])->orderBy('name')->paginate(15),'kind'=>'categories']); }
    public function priorities() { return view('admin.catalog.index',['items'=>CleaningPriority::withCount(['tasks','requests'])->orderBy('sort_order')->paginate(15),'kind'=>'priorities']); }
    public function storeLocation(Request $request) { $this->save(CampusLocation::class,$request,['name'=>['required','string','max:150','unique:campus_locations,name'],'building'=>['nullable','string','max:150'],'description'=>['nullable','string','max:1000']]); return back()->with('success','Campus location added.'); }
    public function updateLocation(Request $request, CampusLocation $location) { $this->save($location,$request,['name'=>['required','string','max:150',Rule::unique('campus_locations','name')->ignore($location->id)],'building'=>['nullable','string','max:150'],'description'=>['nullable','string','max:1000']]); return back()->with('success','Campus location updated.'); }
    public function deleteLocation(CampusLocation $location) { if($location->tasks()->exists()||$location->requests()->exists()) return back()->withErrors(['catalog'=>'This location is in use. Deactivate it to preserve task history.']); $location->delete(); return back()->with('success','Campus location removed.'); }
    public function storeCategory(Request $request) { $this->save(CleaningCategory::class,$request,['name'=>['required','string','max:80','unique:cleaning_categories,name'],'description'=>['nullable','string','max:1000']]); return back()->with('success','Cleaning category added.'); }
    public function updateCategory(Request $request, CleaningCategory $category) { $this->save($category,$request,['name'=>['required','string','max:80',Rule::unique('cleaning_categories','name')->ignore($category->id)],'description'=>['nullable','string','max:1000']]); return back()->with('success','Cleaning category updated.'); }
    public function deleteCategory(CleaningCategory $category) { if($category->tasks()->exists()||$category->requests()->exists()) return back()->withErrors(['catalog'=>'This category is in use. Deactivate it to preserve task history.']); $category->delete(); return back()->with('success','Cleaning category removed.'); }
    public function toggleLocation(CampusLocation $location) { $location->update(['is_active'=>!$location->is_active]); return back()->with('success','Location availability updated.'); }
    public function toggleCategory(CleaningCategory $category) { $category->update(['is_active'=>!$category->is_active]); return back()->with('success','Category availability updated.'); }
    public function togglePriority(CleaningPriority $priority) { $priority->update(['is_active'=>!$priority->is_active]); return back()->with('success','Priority availability updated. Existing task history was preserved.'); }

    private function save($model,Request $request,array $rules): void
    {
        $data=$request->validate($rules);
        if(is_string($model)) $model=new $model();
        $model->fill($data)->save();
    }
}
