<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Todo;

class TodoController extends Controller
{
    public function index(){
        $user = Auth::user();
        $todos = Todo::where('user_id', $user->id)->orderBy('created_at','desc')->get();
        return view('todos.index', compact('todos'));
    }

    public function create(){ return view('todos.create'); }

    public function store(Request $r){
        $r->validate(['title'=>'required']);
        $t = Todo::create([
            'user_id' => Auth::id(),
            'title' => $r->title,
            'completed' => false
        ]);
        return redirect('/todos');
    }

    public function show($id){ return redirect('/todos'); }

    public function edit($id){
        $todo = Todo::findOrFail($id);
        $this->authorizeTodo($todo);
        return view('todos.edit', compact('todo'));
    }

    public function update(Request $r, $id){
        $todo = Todo::findOrFail($id);
        $this->authorizeTodo($todo);
        $todo->title = $r->title;
        $todo->completed = $r->has('completed');
        $todo->save();
        return redirect('/todos');
    }

    public function destroy($id){
        $todo = Todo::findOrFail($id);
        $this->authorizeTodo($todo);
        $todo->delete();
        return redirect('/todos');
    }

    private function authorizeTodo($todo){
        if($todo->user_id !== Auth::id()) abort(403);
    }
}
