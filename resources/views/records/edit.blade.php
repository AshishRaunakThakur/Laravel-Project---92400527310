@extends('layouts.app')
@section('title','Edit cleaning task')@section('crumb','Edit task')
@section('content')<div class="welcome-row compact"><div><div class="eyebrow">TASK MANAGEMENT</div><h1>Edit cleaning task</h1><p class="subtitle">Update the details for {{ $record->location }}.</p></div></div><div class="panel form-panel">@include('records._form',['action'=>route('admin.records.update',$record),'method'=>'PUT','submitLabel'=>'Save changes'])</div>@endsection
