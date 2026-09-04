<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Tenant' }}</title>

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

        .layout {
            display: flex;
            min-height: 100vh;
        }

        aside {
            width: 220px;
            padding: 20px;
            background: #4F8276;
            color: white;
        }

        aside h2 {
            margin-top: 0;
        }

        nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        nav a {
            display: block;
            padding: 12px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            min-height: 44px;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        main {
            flex: 1;
            padding: 20px;
            max-width: 1024px;
        }

        .menu-button {
            display: none;
        }

        @media (max-width: 767px) {
            .layout {
                display: block;
            }

            aside {
                width: 100%;
            }

            .menu-button {
                display: inline-block;
                margin-bottom: 12px;
                padding: 10px 14px;
                min-height: 44px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
            }

            nav {
                display: none;
            }

            nav.show {
                display: flex;
            }

            main {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="layout">
        <aside>
            <h2>Tenant</h2>

            <button
                type="button"
                class="menu-button"
                onclick="document.querySelector('nav').classList.toggle('show')"
            >
                Menu
            </button>

            <nav>
                <a href="#">Dashboard</a>
                <a href="#">Produk</a>
                <a href="#">Pesanan</a>
                <a href="#">Pembayaran</a>
            </nav>
        </aside>

        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>