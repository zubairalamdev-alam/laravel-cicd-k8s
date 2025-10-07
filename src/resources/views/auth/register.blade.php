@extends('layout')
@section('title','Register')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <h3>Register</h3>
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <form method="POST" action="/register">@csrf
      <div class="mb-3"><label>Name</label><input class="form-control" name="name"></div>
      <div class="mb-3"><label>Email</label><input class="form-control" name="email"></div>
      <div class="mb-3"><label>Password</label><input type="password" class="form-control" name="password"></div>
      <div class="mb-3"><label>Confirm</label><input type="password" class="form-control" name="password_confirmation"></div>
      <button class="btn btn-primary">Register</button>
    </form>
  </div>
</div>
@endsection
