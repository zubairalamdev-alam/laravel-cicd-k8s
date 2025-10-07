@extends('layout')
@section('title','Home')
@section('content')
<div class="d-flex justify-content-between align-items-center">
  <h2>Welcome, {{ $user->name }}</h2>
  <a href="/todos" class="btn btn-secondary">My Todos</a>
</div>
@endsection
