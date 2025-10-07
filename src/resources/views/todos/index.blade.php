@extends('layout')
@section('title','Todos')
@section('content')
<div class="d-flex justify-content-between mb-3">
  <h3>Your Todos</h3>
  <a class="btn btn-primary" href="/todos/create">New</a>
</div>
<table class="table">
  <thead><tr><th>Title</th><th>Completed</th><th>Actions</th></tr></thead>
  <tbody>
    @foreach($todos as $t)
    <tr>
      <td>{{ $t->title }}</td>
      <td>{{ $t->completed ? 'Yes' : 'No' }}</td>
      <td>
        <a class="btn btn-sm btn-secondary" href="/todos/{{ $t->id }}/edit">Edit</a>
        <form method="POST" action="/todos/{{ $t->id }}" style="display:inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
