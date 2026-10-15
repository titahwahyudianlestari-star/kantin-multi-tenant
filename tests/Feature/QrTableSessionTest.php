<?php

namespace Tests\Feature;

use App\Models\Canteen;
use App\Models\CustomerSession;
use App\Models\DiningTable;
use App\Models\TableQrToken;
use App\Models\User;
use App\Models\UserCanteenRole;
use App\Modules\Admin\Services\QrTokenService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class QrTableSessionTest extends TestCase
{
    use RefreshDatabase;

    private function canteen(
        string $status = 'active',
        ?string $code = null
    ): Canteen {
        $code ??= 'KNT'.Str::upper(Str::random(6));

        $canteen = new Canteen;

        $canteen->forceFill([
            'code' => $code,
            'slug' => Str::lower($code),
            'tax_rate' => 0,
            'service_fee_rate' => 0,
            'status' => $status,
        ])->save();

        return $canteen;
    }

    private function managerFor(Canteen $canteen): User
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $role = new UserCanteenRole;

        $role->forceFill([
            'user_id' => $user->id,
            'canteen_id' => $canteen->id,
            'role' => 'manager',
        ])->save();

        return $user;
    }

    private function table(
        ?Canteen $canteen = null,
        string $status = 'active',
        ?string $code = null
    ): DiningTable {
        $canteen ??= $this->canteen();
        $code ??= 'T'.Str::upper(Str::random(6));

        $table = new DiningTable;

        $table->forceFill([
            'canteen_id' => $canteen->id,
            'code' => $code,
            'label' => 'Meja Test',
            'zone' => 'Indoor',
            'status' => $status,
        ])->save();

        return $table;
    }

    public function test_dining_table_code_unique_per_canteen(): void
    {
        $canteenA = $this->canteen();
        $canteenB = $this->canteen();

        $this->table(
            $canteenA,
            'active',
            'T1'
        );

        $tableB = $this->table(
            $canteenB,
            'active',
            'T1'
        );

        $this->assertDatabaseHas('dining_tables', [
            'canteen_id' => $canteenA->id,
            'code' => 'T1',
        ]);

        $this->assertDatabaseHas('dining_tables', [
            'canteen_id' => $canteenB->id,
            'code' => 'T1',
        ]);

        $this->assertSame(
            $canteenB->id,
            $tableB->canteen_id
        );

        try {
            $this->table(
                $canteenA,
                'active',
                'T1'
            );

            $this->fail(
                'Kode meja yang sama pada kantin yang sama seharusnya ditolak.'
            );
        } catch (QueryException $exception) {
            $this->assertNotEmpty(
                $exception->getMessage()
            );
        }

        $this->assertSame(
            1,
            DiningTable::query()
                ->where(
                    'canteen_id',
                    $canteenA->id
                )
                ->where(
                    'code',
                    'T1'
                )
                ->count()
        );
    }

    public function test_non_manager_cannot_manage_tables(): void
    {
        $this->canteen();

        $outsider = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($outsider);

        $this->get(
            route('admin.tables.index')
        )->assertForbidden();
    }

    public function test_rotate_revokes_old_token_and_keeps_single_active(): void
    {
        $table = $this->table();

        $qr = app(
            QrTokenService::class
        );

        $plain1 = $qr->issue($table);

        $this->get(
            route('customer.scan', [
                'token' => $plain1,
            ])
        )->assertRedirect();

        $plain2 = $qr->rotate($table);

        // QR lama harus langsung tidak berlaku.
        $this->get(
            route('customer.scan', [
                'token' => $plain1,
            ])
        )->assertNotFound();

        // QR baru tetap valid.
        $this->get(
            route('customer.scan', [
                'token' => $plain2,
            ])
        )->assertRedirect();

        $this->assertSame(
            1,
            TableQrToken::query()
                ->where(
                    'dining_table_id',
                    $table->id
                )
                ->where(
                    'status',
                    'active'
                )
                ->count()
        );

        $this->assertSame(
            1,
            TableQrToken::query()
                ->where(
                    'dining_table_id',
                    $table->id
                )
                ->where(
                    'status',
                    'revoked'
                )
                ->count()
        );
    }

    public function test_valid_scan_creates_bound_session_with_secure_cookie(): void
    {
        $canteen = $this->canteen();

        $table = $this->table(
            $canteen
        );

        $plain = app(
            QrTokenService::class
        )->issue($table);

        $response = $this->get(
            route('customer.scan', [
                'token' => $plain,
            ])
        );

        /*
         * Tahap 8:
         * scan valid sekarang diarahkan ke
         * halaman sambutan meja.
         */
        $response->assertRedirect(
            route('customer.welcome', [
                'canteen' => $canteen->slug,
            ])
        );

        $session = CustomerSession::query()
            ->firstOrFail();

        $this->assertSame(
            $table->id,
            $session->dining_table_id
        );

        $this->assertNotNull(
            $session->table_qr_token_id
        );

        $this->assertSame(
            'active',
            $session->status
        );

        $this->assertSame(
            32,
            strlen(
                $session->session_token_hash
            )
        );

        $response->assertCookie(
            'customer_session'
        );

        $cookie = $response->getCookie(
            'customer_session',
            false
        );

        $this->assertNotNull(
            $cookie
        );

        $this->assertTrue(
            $cookie->isHttpOnly()
        );

        $this->assertSame(
            'lax',
            strtolower(
                $cookie->getSameSite()
            )
        );
    }

    public function test_invalid_tokens_are_generic_404(): void
    {
        // Token acak.
        $this->get(
            route('customer.scan', [
                'token' => 'tidak-ada-token',
            ])
        )
            ->assertNotFound()
            ->assertSee(
                'hubungi petugas kantin'
            );

        // ID numerik tidak boleh dipakai untuk enumeration.
        $this->get(
            route('customer.scan', [
                'token' => '1',
            ])
        )->assertNotFound();

        // Token revoked.
        $table = $this->table();

        $plain = app(
            QrTokenService::class
        )->issue($table);

        app(
            QrTokenService::class
        )->rotate($table);

        $this->get(
            route('customer.scan', [
                'token' => $plain,
            ])
        )->assertNotFound();

        // Token expired.
        $expiredTable = $this->table();

        $expiredPlain = app(
            QrTokenService::class
        )->issue(
            $expiredTable,
            now()->subMinute()
        );

        $this->get(
            route('customer.scan', [
                'token' => $expiredPlain,
            ])
        )->assertNotFound();

        // Kantin inactive.
        $inactiveCanteen = $this->canteen(
            'inactive'
        );

        $table2 = $this->table(
            $inactiveCanteen
        );

        $plain2 = app(
            QrTokenService::class
        )->issue($table2);

        $this->get(
            route('customer.scan', [
                'token' => $plain2,
            ])
        )->assertNotFound();

        // Meja inactive.
        $table3 = $this->table(
            null,
            'inactive'
        );

        $plain3 = app(
            QrTokenService::class
        )->issue($table3);

        $this->get(
            route('customer.scan', [
                'token' => $plain3,
            ])
        )->assertNotFound();
    }
}
