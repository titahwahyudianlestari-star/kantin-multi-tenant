<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8faf9;
            color: #333;
        }

        header {
            padding: 16px;
            background: #4F8276;
            color: white;
        }

        header h1 {
            margin: 0 0 12px;
        }

        nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        nav a {
            display: inline-block;
            padding: 10px 12px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            min-height: 44px;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        main {
            width: 100%;
            max-width: 1024px;
            margin: 0 auto;
            padding: 16px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 600px;
            border-collapse: collapse;
            background: white;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        @media (min-width: 768px) {
            main {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin</h1>

        <nav>
            <a href="#">Dashboard</a>
            <a href="#">Pengguna</a>
            <a href="#">Laporan</a>
        </nav>
    </header>

    <main>
        {{ $slot }}
    </main>
</body>
</html>