<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $canteen->name }}</title>
</head>
<body>

    @if ($table)

        <p>
            <strong>Sesi pemesanan aktif</strong>
        </p>

        <h1>
            Selamat datang, {{ $table->label }}
            @if ($table->zone)
                — {{ $table->zone }}
            @endif
        </h1>

        <p>
            QR meja tervalidasi.
            Pilih tenant untuk mulai memesan.
        </p>

    @else

        <h1>Pindai QR meja untuk memulai</h1>

        <p>
            Sesi pemesanan dibuat otomatis setelah
            Anda memindai QR Code pada meja.
        </p>

    @endif

    <h2>Daftar Tenant</h2>

    <ul>
        @forelse ($tenants as $tenant)

            @php
                $open = $hours->isOpen($tenant);
            @endphp

            <li>
                <strong>
                    {{ $tenant->display_name ?? $tenant->code }}
                </strong>

                <br>

                @if ($open)
                    Buka

                    @if ($hours->todayLabel($tenant))
                        · {{ $hours->todayLabel($tenant) }}
                    @endif
                @else
                    Tutup

                    @if ($hours->opensAtToday($tenant))
                        · Buka pukul
                        {{ $hours->opensAtToday($tenant) }}
                    @else
                        · Libur hari ini
                    @endif
                @endif
            </li>

            <br>

        @empty

            <li>
                Belum ada tenant aktif.
            </li>

        @endforelse
    </ul>

    @if ($table)

        <br>

        <a
            href="{{ route('customer.home', ['canteen' => $canteen->slug]) }}"
        >
            Lihat semua menu
        </a>

    @endif

</body>
</html>