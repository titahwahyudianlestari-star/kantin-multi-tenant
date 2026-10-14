<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Meja {{ $table->code }}</title>
</head>
<body>

    <h1>
        QR Meja {{ $table->label }} ({{ $table->code }})
    </h1>

    @if ($url)
        <p>
            Token QR baru hanya ditampilkan satu kali.
            Simpan URL ini sekarang.
        </p>

        <p>
            <strong>URL QR:</strong>
        </p>

        <p style="word-break: break-all;">
            {{ $url }}
        </p>

        <p>
            Setelah halaman direfresh, URL ini tidak akan ditampilkan lagi.
        </p>
    @else
        <p>
            Tidak ada token baru untuk ditampilkan.
        </p>
    @endif

    <br>

    <a href="{{ route('admin.tables.index') }}">
        Kembali ke daftar meja
    </a>

</body>
</html>