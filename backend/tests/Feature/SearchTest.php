<?php

namespace Tests\Feature;

use App\Models\SitterProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_suche_nach_stadt_gibt_passende_sitter_zurueck(): void
    {
        // Einen Sitter in München anlegen
        // User::factory() erstellt einen Fake-User mit zufälligen Daten
        $sitter = User::factory()->create(['role' => 'sitter']);
        SitterProfile::factory()->create([
            'user_id' => $sitter->id,
            'city' => 'München',
            'lat' => 48.1351,
            'lng' => 11.5820,
        ]);

        // Einen Sitter in Hamburg anlegen — soll NICHT in den Ergebnissen auftauchen
        $andererSitter = User::factory()->create(['role' => 'sitter']);
        SitterProfile::factory()->create([
            'user_id' => $andererSitter->id,
            'city' => 'Hamburg',
            'lat' => 53.5753,
            'lng' => 10.0153,
        ]);

        // Suche nach München
        $response = $this->getJson('/api/sitters?city=München');

        $response->assertStatus(200);

        // Nur 1 Ergebnis (München), nicht Hamburg
        $response->assertJsonCount(1);

        // Das Ergebnis muss München sein
        $response->assertJsonFragment(['city' => 'München']);
    }

    public function test_inaktive_sitter_erscheinen_nicht_in_suche(): void
    {
        $sitter = User::factory()->create(['role' => 'sitter']);
        SitterProfile::factory()->create([
            'user_id' => $sitter->id,
            'city' => 'München',
            'is_active' => false, // deaktiviert
        ]);

        $response = $this->getJson('/api/sitters?city=München');

        $response->assertStatus(200);
        $response->assertJsonCount(0); // keine Ergebnisse
    }

    public function test_suche_ohne_parameter_gibt_fehler(): void
    {
        $response = $this->getJson('/api/sitters');

        // 422 = Validierungsfehler, weil weder city noch lat/lng angegeben
        $response->assertStatus(422);
    }
}
