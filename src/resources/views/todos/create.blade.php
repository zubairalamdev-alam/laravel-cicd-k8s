@extends('layout')
@section('title','Create Todo')
@section('content')
<h3>Create Todo</h3>
<form method="POST" action="/todos">@csrf
  <div class="mb-3"><input class="form-control" name="title" placeholder="Title"></div>
  <button class="btn btn-primary">Save</button>
</form>
@endsection
