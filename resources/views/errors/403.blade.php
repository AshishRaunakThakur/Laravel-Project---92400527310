@extends('layouts.app')
@section('title','Access denied')
@section('content')<div class="empty-state error-page"><span class="empty-icon"><i class="bi bi-shield-lock"></i></span><strong>That page is restricted</strong><p>Your account does not have permission to view this page.</p><a href="{{ route('dashboard') }}" class="btn btn-primary">Back to my dashboard</a></div>@endsection
