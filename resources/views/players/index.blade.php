<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Players - Archery Tracker</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #0f172a;
            padding: 20px;
            color: #e2e8f0;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        header {
            background: #1e293b;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #334155;
        }

        h1 {
            font-size: 1.8rem;
            color: #f1f5f9;
        }

        .btn {
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            transition: background 0.3s;
            display: inline-block;
        }

        .btn:hover {
            background: #45a049;
        }

        .btn-secondary {
            background: #2196F3;
        }

        .btn-secondary:hover {
            background: #0b7dda;
        }

        .btn-danger {
            background: #f44336;
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .btn-danger:hover {
            background: #da190b;
        }

        .panel {
            background: #1e293b;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 20px;
            border: 1px solid #334155;
        }

        h2 {
            font-size: 1.3rem;
            color: #f1f5f9;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #cbd5e1;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #334155;
            border-radius: 6px;
            font-size: 1rem;
            background: #0f172a;
            color: #e2e8f0;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .players-table {
            width: 100%;
            border-collapse: collapse;
        }

        .players-table th {
            background: #0f172a;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #cbd5e1;
            border-bottom: 2px solid #334155;
        }

        .players-table td {
            padding: 12px;
            border-bottom: 1px solid #334155;
            color: #e2e8f0;
        }

        .players-table tr:hover {
            background: #334155;
        }

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #064e3b;
            color: #6ee7b7;
            border: 1px solid #10b981;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .delete-form {
            display: inline;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🎯 Player Management</h1>
            <a href="/" class="btn btn-secondary">Back to Game</a>
        </header>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="panel">
            <h2>Add New Player</h2>
            <form action="{{ route('players.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Player Name</label>
                    <input type="text" id="name" name="name" required>
                    @error('name')
                        <span style="color: #f44336; font-size: 0.85rem; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn">Add Player</button>
            </form>
        </div>

        <div class="panel">
            <h2>All Players</h2>
            @if($players->isEmpty())
                <p style="color: #666;">No players yet. Add one above!</p>
            @else
                <table class="players-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Games Played</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($players as $player)
                            <tr>
                                <td>{{ $player->name }}</td>
                                <td>{{ $player->games_count }}</td>
                                <td>
                                    <form action="{{ route('players.destroy', $player) }}" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete {{ $player->name }}? This will also delete all their games.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</body>
</html>
