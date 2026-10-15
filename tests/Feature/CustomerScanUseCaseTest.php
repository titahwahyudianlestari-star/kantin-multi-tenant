<?php

namespace Tests\Feature;

use App\Models\Canteen;
use App\Models\CustomerSession;
use App\Models\DiningTable;
use App\Models\Tenant;
use App\Models\TenantOperatingHour;
use App\Modules\Admin\Services\QrTokenService;
use App\Modules\Catalog\Services\TenantOpeningHours;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerScanUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private function canteen(): Canteen
    {
        $code = 'KNT'.Str::upper(Str::random(6));

        $canteen = new Canteen;

        $canteen->forceFill([
            'code' => $code,
            'slug' => Str::lower($code),
            'tax_rate' => 0,
            'service_fee_rate' => 0,
            'status' => 'active',
        ])->save();

        return $canteen;
    }

    private function table(
        Canteen $canteen
    ): DiningTable {
        $table = new DiningTable;

        $table->forceFill([
            'canteen_id' => $canteen->id,
            'code' => 'M12',
            'label' => 'Meja 12',
            'zone' => 'Zona A',
            'status' => 'active',
        ])->save();

        return $table;
    }

    private function tenant(
        Canteen $canteen,
        string $code
    ): Tenant {
        $tenant = new Tenant;

        $tenant->forceFill([
            'canteen_id' => $canteen->id,
            'code' => $code,
            'slug' => strtolower($code),
            'status' => 'active',
        ])->save();

        return $tenant;
    }

    private function hour(
        Tenant $tenant,
        string $day,
        string $opensAt,
        string $closesAt
    ): void {
        $hour = new TenantOperatingHour;

        $hour->forceFill([
            'tenant_id' => $tenant->id,
            'day' => $day,
            'opens_at' => $opensAt,
            'closes_at' => $closesAt,
        ])->saveQuietly();
    }

    /**
     * @return array{
     *     0: Canteen,
     *     1: DiningTable,
     *     2: Tenant,
     *     3: Tenant
     * }
     */
    private function setupCanteenWithHours(): array
    {
        $canteen = $this->canteen();

        $table = $this->table(
            $canteen
        );

        $rina = $this->tenant(
            $canteen,
            'RINA'
        );

        $geprek = $this->tenant(
            $canteen,
            'GEPREK'
        );

        $days = [
            'sunday',
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
        ];

        foreach ($days as $day) {
            $this->hour(
                $rina,
                $day,
                '07:00:00',
                '20:00:00'
            );

            $this->hour(
                $geprek,
                $day,
                '10:00:00',
                '20:00:00'
            );
        }

        return [
            $canteen,
            $table,
            $rina,
            $geprek,
        ];
    }

    public function test_scan_during_hours_greets_table_and_lists_tenant_status(): void
    {
        [
            $canteen,
            $table,
        ] = $this->setupCanteenWithHours();

        $this->travelTo(
            Carbon::parse(
                '2026-09-24 08:30:00',
                'Asia/Jakarta'
            )
        );

        $plain = app(
            QrTokenService::class
        )->issue($table);

        $scan = $this->get(
            route(
                'customer.scan',
                [
                    'token' => $plain,
                ]
            )
        );

        $scan->assertRedirect(
            route(
                'customer.welcome',
                [
                    'canteen' => $canteen->slug,
                ]
            )
        );

        $cookie = $scan->getCookie(
            'customer_session'
        );

        $this->assertNotNull(
            $cookie
        );

        $response = $this
            ->withCookie(
                'customer_session',
                $cookie->getValue()
            )
            ->get(
                route(
                    'customer.welcome',
                    [
                        'canteen' => $canteen->slug,
                    ]
                )
            );

        $response
            ->assertOk()
            ->assertSee(
                'Sesi pemesanan aktif'
            )
            ->assertSee(
                'Meja 12'
            )
            ->assertSee(
                'Zona A'
            )
            ->assertSee(
                'RINA'
            )
            ->assertSee(
                'GEPREK'
            )
            ->assertSee(
                'Buka'
            )
            ->assertSee(
                'Tutup'
            )
            ->assertSee(
                'Lihat semua menu'
            );

        $this->assertSame(
            1,
            CustomerSession::query()->count()
        );
    }

    public function test_scan_outside_hours_shows_closed_page_without_creating_session(): void
    {
        [
            ,
            $table,
        ] = $this->setupCanteenWithHours();

        $this->travelTo(
            Carbon::parse(
                '2026-09-24 22:00:00',
                'Asia/Jakarta'
            )
        );

        $plain = app(
            QrTokenService::class
        )->issue($table);

        $response = $this->get(
            route(
                'customer.scan',
                [
                    'token' => $plain,
                ]
            )
        );

        $response
            ->assertOk()
            ->assertSee(
                'Kantin sedang tutup'
            )
            ->assertSee(
                '07.00–20.00'
            )
            ->assertCookieMissing(
                'customer_session'
            );

        $this->assertSame(
            0,
            CustomerSession::query()->count()
        );
    }

    public function test_invalid_token_returns_generic_404_with_staff_message(): void
    {
        $response = $this->get(
            route(
                'customer.scan',
                [
                    'token' => 'tidak-ada-token',
                ]
            )
        );

        $response
            ->assertNotFound()
            ->assertSee(
                'hubungi petugas kantin'
            );
    }

    public function test_welcome_without_session_asks_user_to_scan_qr(): void
    {
        [
            $canteen,
        ] = $this->setupCanteenWithHours();

        $response = $this->get(
            route(
                'customer.welcome',
                [
                    'canteen' => $canteen->slug,
                ]
            )
        );

        $response
            ->assertOk()
            ->assertSee(
                'Pindai QR meja untuk memulai'
            )
            ->assertDontSee(
                'Lihat semua menu'
            );
    }

    public function test_overnight_hours_and_unconfigured_tenant_count_as_open(): void
    {
        $canteen = $this->canteen();

        $night = $this->tenant(
            $canteen,
            'NIGHT'
        );

        $this->hour(
            $night,
            'thursday',
            '18:00:00',
            '02:00:00'
        );

        $hours = app(
            TenantOpeningHours::class
        );

        $this->assertTrue(
            $hours->isOpen(
                $night,
                Carbon::parse(
                    '2026-09-24 23:30:00',
                    'Asia/Jakarta'
                )
            )
        );

        $this->assertFalse(
            $hours->isOpen(
                $night,
                Carbon::parse(
                    '2026-09-24 12:00:00',
                    'Asia/Jakarta'
                )
            )
        );

        $unconfigured = $this->tenant(
            $canteen,
            'FREE'
        );

        $this->assertTrue(
            $hours->isOpen(
                $unconfigured
            )
        );
    }
}
