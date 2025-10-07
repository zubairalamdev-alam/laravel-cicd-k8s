@extends('layout')
@section('title','Login')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <h3>Login</h3>
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <form method="POST" action="/login">@csrf
      <div class="mb-3"><label>Email</label><input class="form-control" name="email"></div>
      <div class="mb-3"><label>Password</label><input type="password" class="form-control" name="password"></div>
      <button class="btn btn-primary">Login</button>
    </form>
  </div>
</div>
@endsection
