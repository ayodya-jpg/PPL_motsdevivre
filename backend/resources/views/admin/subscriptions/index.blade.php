<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Langganan Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Daftar Langganan Pengguna</h2>

    <table class="table table-bordered table-striped mt-3">
        <thead>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Email</th>
            <th>Paket</th>
            <th>Status</th>
            <th>Mulai</th>
            <th>Berakhir</th>
            <th>Dibuat</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($subscriptions as $sub)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sub->user->name ?? '-' }}</td>
                <td>{{ $sub->user->email ?? '-' }}</td>
                <td>{{ $sub->plan_name }}</td>
                <td>{{ $sub->status }}</td>
                <td>{{ $sub->started_at }}</td>
                <td>{{ $sub->ends_at }}</td>
                <td>{{ $sub->created_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada data langganan.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
