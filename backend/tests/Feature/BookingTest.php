<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\SitterProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_hundebesitzer_kann_buchungsanfrage_senden(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $sitter = User::factory()->create(['role' => 'sitter']);
        SitterProfile::factory()->create(['user_id' => $sitter->id]);

        // actingAs() simuliert einen eingeloggten User mit Sanctum-Token
        $response = $this->actingAs($owner)->postJson('/api/bookings', [
            'sitter_id' => $sitter->id,
            'dog_name' => 'Bello',
            'dog_size' => 'medium',
            'from_date' => now()->addDays(5)->toDateString(),
            'to_date' => now()->addDays(10)->toDateString(),
            'message' => 'Bitte gut auf ihn aufpassen!',
        ]);

        $response->assertStatus(201);

        // Buchung muss in der DB sein
        $this->assertDatabaseHas('bookings', [
            'owner_id' => $owner->id,
            'sitter_id' => $sitter->id,
            'status' => 'pending', // startet immer als "ausstehend"
        ]);
    }

    public function test_nicht_eingeloggter_user_kann_nicht_buchen(): void
    {
        $sitter = User::factory()->create();

        // Kein actingAs() → nicht eingeloggt
        $response = $this->postJson('/api/bookings', [
            'sitter_id' => $sitter->id,
            'dog_name' => 'Bello',
            'dog_size' => 'medium',
            'from_date' => now()->addDays(5)->toDateString(),
            'to_date' => now()->addDays(10)->toDateString(),
        ]);

        // 401 = Unauthorized
        $response->assertStatus(401);
    }

    public function test_sitter_kann_buchung_bestaetigen(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $sitter = User::factory()->create(['role' => 'sitter']);

        // Direkt eine Buchung in DB anlegen (kein HTTP nötig für das Setup)
        $booking = Booking::factory()->create([
            'owner_id' => $owner->id,
            'sitter_id' => $sitter->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($sitter)
            ->putJson("/api/bookings/{$booking->id}/confirm");

        $response->assertStatus(200);

        // Status in DB muss nun "confirmed" sein
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_fremder_sitter_kann_buchung_nicht_bestaetigen(): void
    {
        $owner = User::factory()->create();
        $sitter = User::factory()->create();
        $andererSitter = User::factory()->create(); // nicht der richtige Sitter!

        $booking = Booking::factory()->create([
            'owner_id' => $owner->id,
            'sitter_id' => $sitter->id,
            'status' => 'pending',
        ]);

        // andererSitter versucht eine fremde Buchung zu bestätigen
        $response = $this->actingAs($andererSitter)
            ->putJson("/api/bookings/{$booking->id}/confirm");

        // 403 = Forbidden
        $response->assertStatus(403);
    }
}
