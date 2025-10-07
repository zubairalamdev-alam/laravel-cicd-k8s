@extends('layout')
@section('title','Edit Todo')
@section('content')
<h3>Edit Todo</h3>
<form method="POST" action="/todos/{{ $todo->id }}">@csrf @method('PUT')
  <div class="mb-3"><input class="form-control" name="title" value="{{ $todo->title }}"></div>
  <div class="mb-3 form-check"><input type="checkbox" class="form-check-input" name="completed" {{ $todo->completed ? 'checked' : '' }}><label class="form-check-label">Completed</label></div>
  <button class="btn btn-primary">Update</button>
</form>
@endsection
