<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        return view('todos.index', ['todos' => Todo::all()]);
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required']);
        Todo::create($request->only('title'));
        return redirect('/');
    }

    public function update(Todo $todo)
    {
        $todo->update(['completed' => !$todo->completed]);
        return redirect('/');
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect('/');
    }
}
