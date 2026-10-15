<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kantin Sedang Tutup</title>
</head>
<body>

    <h1>Kantin sedang tutup</h1>

    <p>
        QR {{ $table->label }} valid,
        tetapi pemesanan sedang ditutup.
    </p>

    <h2>Jam operasional tenant hari ini</h2>

    <ul>
        @forelse ($tenants as $tenant)
            <li>
                <strong>
                    {{ $tenant->display_name ?? $tenant->code }}
                </strong>

                —
                {{ $hours->todayLabel($tenant) ?? 'Libur hari ini' }}
            </li>
        @empty
            <li>
                Belum ada tenant aktif.
            </li>
        @endforelse
    </ul>

</body>
</html>