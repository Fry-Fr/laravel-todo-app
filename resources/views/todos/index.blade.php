<!DOCTYPE html>
<html>
<head>
    <title>ToDo App</title>
</head>
<body>
    <h1>My ToDo List</h1>

    <form method="POST" action="/todos">
        @csrf
        <input type="text" name="title" placeholder="New Task" required>
        <button type="submit">Add</button>
    </form>

    <ul>
        @foreach ($todos as $todo)
            <li>
                <form method="POST" action="/todos/{{ $todo->id }}" style="display:inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit">{{ $todo->completed ? 'Undo' : 'Complete' }}</button>
                </form>

                {{ $todo->title }} - {{ $todo->completed ? 'Done' : 'Pending' }}

                <form method="POST" action="/todos/{{ $todo->id }}" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>
`
