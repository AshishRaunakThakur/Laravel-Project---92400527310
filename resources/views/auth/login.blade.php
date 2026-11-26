@extends('layouts.guest')
@section('title','Sign in')
@section('card-class','login-card')
@section('wrap-class','login-wrap')
@section('content')
<div class="auth-icon"><i class="bi bi-stars"></i></div><div class="eyebrow">WELCOME BACK</div><h1>Sign in to CampusCare</h1><p class="subtitle">Use your campus account to continue.</p>
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('login.store') }}" class="auth-form">@csrf<div><label class="form-label" for="email">Email address</label><input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus></div><div><label class="form-label" for="password">Password</label><div class="password-field"><input class="form-control" type="password" id="password" name="password" autocomplete="current-password" required><button class="password-visibility" type="button" aria-label="Show password" aria-pressed="false" data-password-toggle="password"><i class="bi bi-eye"></i></button></div></div><label class="remember-row"><input type="checkbox" name="remember" value="1"> Keep me signed in</label><button class="btn btn-primary w-100" type="submit">Sign in <i class="bi bi-arrow-right ms-1"></i></button></form>
<div class="auth-switch">New to CampusCare? <a href="{{ route('register') }}">Create a campus account</a></div><div class="auth-note"><i class="bi bi-shield-lock"></i> Staff and administrator accounts are created by an administrator.</div>
<script>document.querySelectorAll('[data-password-toggle]').forEach(function(button){button.addEventListener('click',function(){var input=document.getElementById(button.dataset.passwordToggle);var visible=input.type==='password';input.type=visible?'text':'password';button.setAttribute('aria-pressed',String(visible));button.setAttribute('aria-label',visible?'Hide password':'Show password');button.innerHTML=visible?'<i class="bi bi-eye-slash"></i>':'<i class="bi bi-eye"></i>';});});</script>
@endsection
