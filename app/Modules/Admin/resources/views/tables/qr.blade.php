<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Meja {{ $table->code }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        .qr-card {
            display: inline-block;
            padding: 24px;
            border: 1px solid #ccc;
            text-align: center;
            background: #fff;
        }

        .qr-image {
            width: 280px;
            margin: 20px auto;
        }

        .qr-image svg {
            width: 100%;
            height: auto;
        }

        .actions {
            margin-top: 20px;
        }

        .actions a,
        .actions button {
            margin-right: 8px;
            padding: 8px 12px;
            cursor: pointer;
        }

        .url {
            word-break: break-all;
            font-family: monospace;
            font-size: 12px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }

            .qr-card {
                border: none;
            }
        }
    </style>
</head>
<body>

    <h1 class="no-print">
        QR Meja {{ $table->label }} ({{ $table->code }})
    </h1>

    @if ($url && $svg)

        <p class="no-print">
            QR baru hanya ditampilkan satu kali.
            Unduh atau cetak sekarang.
        </p>

        <div class="qr-card">
            <strong>{{ config('app.name') }}</strong>

            <h2>
                {{ $table->label }}
            </h2>

            @if ($table->zone)
                <p>
                    {{ $table->zone }}
                </p>
            @endif

            <div class="qr-image">
                {!! $svg !!}
            </div>

            <p>
                Pindai untuk memesan
            </p>
        </div>

        <div class="actions no-print">

            <a
                href="data:image/svg+xml;base64,{{ base64_encode($svg) }}"
                download="qr-{{ $table->code }}.svg"
            >
                Unduh QR (SVG)
            </a>

            <button
                type="button"
                onclick="window.print()"
            >
                Cetak
            </button>

        </div>

        <p class="no-print">
            <strong>URL QR:</strong>
        </p>

        <p class="url no-print">
            {{ $url }}
        </p>

        <p class="no-print">
            Setelah halaman direfresh, QR ini tidak akan ditampilkan lagi.
        </p>

    @else

        <p>
            Tidak ada QR baru untuk ditampilkan.
        </p>

        <p>
            Kembali ke daftar meja lalu klik
            Terbitkan QR atau Rotate QR.
        </p>

    @endif

    <br>

    <a
        class="no-print"
        href="{{ route('admin.tables.index') }}"
    >
        Kembali ke daftar meja
    </a>

</body>
</html>