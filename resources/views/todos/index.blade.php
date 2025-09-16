<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ToDo App</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #fff;
        }
        ul {
            list-style-type: none;
            padding: 0;
            width: 300px;
        }
        li {
            padding: 10px;
            margin: 5px 0;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            cursor: grab;
        }
        li.dragging {
            opacity: 0.5;
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <h1>My ToDo List</h1>

    <form method="POST" action="/todos">
        @csrf
        <input type="text" name="title" placeholder="New Task" required>
        <button type="submit">Add</button>
    </form>

    <ul id="sortable-list">
        @foreach ($todos as $todo)
            <li draggable="true" data-id="{{ $todo->id }}">
                {!! $todo->completed ? "<strong>Done</strong>" : "<i>Pending</i>" !!} - {{ $todo->title }}

                <br/>
                <div style="margin:5px auto 0px; display:inline-flex; gap:5px; align-items:center; justify-content:center;">
                    <form method="POST" action="/todos/{{ $todo->id }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
    
                    <form method="POST" action="/todos/{{ $todo->id }}" style="display:inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit">{{ $todo->completed ? 'Undo' : 'Complete' }}</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const list = document.getElementById('sortable-list');
            let draggedItem = null;

            list.addEventListener('dragstart', (e) => {
                draggedItem = e.target;
                e.target.classList.add('dragging');
            });

            list.addEventListener('dragend', (e) => {
                e.target.classList.remove('dragging');
                draggedItem = null;
                // Optional: Send updated order to server
                updateOrder();
            });

            list.addEventListener('dragover', (e) => {
                e.preventDefault(); // Allow drop
            });

            list.addEventListener('dragenter', (e) => {
                if (e.target.tagName === 'LI') {
                    e.target.style.borderBottom = '2px solid #97caffff';
                }
            });

            list.addEventListener('dragleave', (e) => {
                if (e.target.tagName === 'LI') {
                    e.target.style.borderBottom = '';
                }
            });

            list.addEventListener('drop', (e) => {
                e.preventDefault();
                if (e.target.tagName === 'LI' && draggedItem !== e.target) {
                    // Reorder the list
                    const allItems = Array.from(list.children);
                    const draggedIndex = allItems.indexOf(draggedItem);
                    const targetIndex = allItems.indexOf(e.target);

                    if (draggedIndex < targetIndex) {
                        e.target.after(draggedItem);
                    } else {
                        e.target.before(draggedItem);
                    }
                    e.target.style.borderBottom = '';
                }
            });

            function updateOrder() {
                const items = Array.from(list.children);
                const order = items.map(item => item.getAttribute('data-id'));
                console.log('New order:', order);
                // Example: Send order to Laravel backend via fetch

                fetch('/update-order', {
                    method: 'PATCH',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ order })
                })
                .then(response => response.json())
                .then(data => console.log('Order updated:', data))
                .catch(error => console.error('Error:', error));
            }
        });
    </script>
</body>
</html>
`
