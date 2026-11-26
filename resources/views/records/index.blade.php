@extends('layouts.app')
@section('title','Cleaning tasks')
@section('crumb','Cleaning tasks')
@section('content')
<div class="welcome-row compact"><div><div class="eyebrow">TASK MANAGEMENT</div><h1>Cleaning tasks</h1><p class="subtitle">Manage assignments, search records and follow work across campus.</p></div><a href="{{ route('admin.records.create') }}" class="btn btn-primary btn-new"><i class="bi bi-plus-lg"></i> New task</a></div>
<div class="panel records-panel">
@php($taskTabs = [['label'=>'All tasks','value'=>'','icon'=>'bi-grid'],['label'=>'Pending','value'=>'Pending','icon'=>'bi-hourglass-split'],['label'=>'In progress','value'=>'In Progress','icon'=>'bi-arrow-repeat'],['label'=>'Completed','value'=>'Completed','icon'=>'bi-check2-circle']])
<nav class="task-status-tabs" aria-label="Filter cleaning tasks by status">
@foreach($taskTabs as $tab)
    @php($tabQuery = request()->except(['status','page']))
    @if($tab['value']) @php($tabQuery['status'] = $tab['value']) @endif
    <a class="task-status-tab {{ request('status','') === $tab['value'] ? 'active' : '' }}" href="{{ route('admin.records.index', $tabQuery) }}"><i class="bi {{ $tab['icon'] }}"></i>{{ $tab['label'] }}</a>
@endforeach
</nav>
<form class="filter-bar records-filter" method="GET" action="{{ route('admin.records.index') }}">
@if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
<div class="search-wrap"><i class="bi bi-search"></i><input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search location, category or staff..."></div>
<select class="form-select" name="priority"><option value="">All priorities</option>@foreach($priorities as $p)<option value="{{ $p->name }}" @selected(request('priority')===$p->name)>{{ $p->name }}</option>@endforeach</select>
<select class="form-select" name="location_id"><option value="">All locations</option>@foreach($locations as $l)<option value="{{ $l->id }}" @selected((string)request('location_id')===(string)$l->id)>{{ $l->name }}</option>@endforeach</select>
<select class="form-select" name="category_id"><option value="">All categories</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected((string)request('category_id')===(string)$c->id)>{{ $c->name }}</option>@endforeach</select>
<select class="form-select" name="assigned_staff_id"><option value="">All staff</option>@foreach($staff as $m)<option value="{{ $m->id }}" @selected((string)request('assigned_staff_id')===(string)$m->id)>{{ $m->name }}</option>@endforeach</select>
<input class="form-control date-filter" type="date" name="date_from" value="{{ request('date_from') }}" aria-label="Scheduled from"><input class="form-control date-filter" type="date" name="date_to" value="{{ request('date_to') }}" aria-label="Scheduled to">
<button class="btn btn-light filter-btn" type="submit">Filter</button>@if(request()->hasAny(['search','status','priority','location_id','category_id','assigned_staff_id','date_from','date_to']))<a class="clear-link" href="{{ route('admin.records.index') }}">Clear</a>@endif
</form>
<div class="table-responsive"><table class="table records-table align-middle mb-0"><thead><tr><th>LOCATION / CATEGORY</th><th>ASSIGNED TO</th><th>PRIORITY</th><th>SCHEDULED</th><th>STATUS</th><th></th></tr></thead><tbody>
@forelse($records as $record)<tr><td><a class="record-title" href="{{ route('admin.records.show',$record) }}">{{ $record->campusLocation?->name ?? $record->location }}</a><span class="record-sub">{{ $record->cleaningCategory?->name ?? $record->category }}</span></td><td><div class="staff-cell"><span class="staff-avatar">{{ strtoupper(substr($record->assignedStaff?->name ?? $record->assigned_staff,0,1)) }}</span>{{ $record->assignedStaff?->name ?? $record->assigned_staff }}</div></td><td><span class="priority priority-{{ strtolower($record->priority) }}"><span></span>{{ $record->priority }}</span></td><td class="date-cell">{{ $record->scheduled_date->format('M d, Y') }}</td><td><span class="badge-status status-{{ \Illuminate\Support\Str::slug($record->status) }}">{{ $record->status }}</span></td><td><div class="dropdown"><button class="dots-btn" data-bs-toggle="dropdown" aria-label="Task actions"><i class="bi bi-three-dots"></i></button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="{{ route('admin.records.show',$record) }}"><i class="bi bi-eye"></i> View details</a></li><li><a class="dropdown-item" href="{{ route('admin.records.edit',$record) }}"><i class="bi bi-pencil"></i> Edit task</a></li><li><hr class="dropdown-divider"></li><li><form method="POST" action="{{ route('admin.records.destroy',$record) }}" onsubmit="return confirm('Delete this task? This cannot be undone.')">@csrf @method('DELETE')<button class="dropdown-item text-danger" type="submit"><i class="bi bi-trash"></i> Delete</button></form></li></ul></div></td></tr>
@empty<tr><td colspan="6"><div class="empty-state table-empty"><span class="empty-icon"><i class="bi bi-clipboard2-x"></i></span><strong>No cleaning tasks found</strong><p>Adjust filters or create a new task.</p><a href="{{ route('admin.records.create') }}" class="text-link">Create a task <i class="bi bi-arrow-right"></i></a></div></td></tr>@endforelse
</tbody></table></div>
@if($records->hasPages())<div class="pagination-wrap"><span>Showing {{ $records->firstItem() }}–{{ $records->lastItem() }} of {{ $records->total() }} tasks</span>{{ $records->links() }}</div>@elseif($records->count())<div class="pagination-wrap"><span>{{ $records->count() }} {{ \Illuminate\Support\Str::plural('task',$records->count()) }}</span></div>@endif
</div>
@endsection
