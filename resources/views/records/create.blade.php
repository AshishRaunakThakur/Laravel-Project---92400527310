@extends('layouts.app')
@section('title','New cleaning task')@section('crumb','New task')
@section('content')<div class="welcome-row compact"><div><div class="eyebrow">TASK MANAGEMENT</div><h1>Create a task</h1><p class="subtitle">Add a cleaning task to the campus schedule.</p></div></div><div class="panel form-panel">@include('records._form',['action'=>route('admin.records.store'),'method'=>'POST','submitLabel'=>'Create task'])</div>@endsection
