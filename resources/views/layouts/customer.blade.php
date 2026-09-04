<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Kantin' }}</title>

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

        main {
            width: 100%;
            max-width: 1024px;
            margin: 0 auto;
            padding: 16px;
        }

        button,
        a {
            min-height: 44px;
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
        <h1>{{ $title ?? 'Kantin' }}</h1>
    </header>

    <main>
        {{ $slot }}
    </main>
</body>
</html>