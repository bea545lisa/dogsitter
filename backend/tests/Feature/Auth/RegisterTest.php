<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// RefreshDatabase: Nach jedem Test wird die DB zurückgesetzt.
// So sind Tests unabhängig voneinander — ein Test beeinflusst den nächsten nicht.
class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_kann_sich_registrieren(): void
    {
        // Wir schicken einen POST-Request an /api/register
        // genau so wie es das Frontend später tun würde
        $response = $this->postJson('/api/register', [
            'name' => 'Max Mustermann',
            'email' => 'max@example.com',
            'password' => 'passwort123',
            'password_confirmation' => 'passwort123',
            'role' => 'owner',
        ]);

        // 201 = "Created" — der Server hat einen neuen User angelegt
        $response->assertStatus(201);

        // Die Antwort muss einen Token enthalten (für spätere API-Requests)
        $response->assertJsonStructure(['user', 'token']);

        // User muss wirklich in der DB stehen
        $this->assertDatabaseHas('users', ['email' => 'max@example.com']);
    }

    public function test_registrierung_schlaegt_fehl_bei_doppelter_email(): void
    {
        // Ersten User direkt in DB anlegen (ohne HTTP-Request)
        User::factory()->create(['email' => 'max@example.com']);

        // Zweiter Registrierungsversuch mit gleicher E-Mail
        $response = $this->postJson('/api/register', [
            'name' => 'Anderer Max',
            'email' => 'max@example.com', // bereits vergeben!
            'password' => 'passwort123',
            'password_confirmation' => 'passwort123',
            'role' => 'owner',
        ]);

        // 422 = Validierungsfehler
        $response->assertStatus(422);

        // Fehlermeldung muss beim email-Feld sein
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_passwort_muss_bestaetigt_werden(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Max',
            'email' => 'max@example.com',
            'password' => 'passwort123',
            'password_confirmation' => 'anderes_passwort', // stimmt nicht überein
            'role' => 'owner',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }
}
