# Testing-Guide

## Warum Tests?

Tests prüfen automatisch ob dein Code das tut was er soll.
Ohne Tests: du klickst manuell durch die App nach jeder Änderung.
Mit Tests: ein Befehl, und du weißt ob alles noch funktioniert.

---

## Backend-Tests (PHPUnit via Laravel)

Laravel bringt PHPUnit direkt mit — du musst nichts extra installieren.

### Wo liegen die Tests?

```
backend/tests/
├── Unit/          → einzelne Klassen/Methoden isoliert testen
│   └── GeoSearchServiceTest.php
└── Feature/       → HTTP-Requests gegen deine API testen (realistischer)
    ├── Auth/
    │   └── RegisterTest.php
    ├── SitterTest.php
    └── BookingTest.php
```

### Beispiel: Feature-Test (das wichtigste!)

```php
// tests/Feature/SitterTest.php

class SitterTest extends TestCase
{
    use RefreshDatabase; // Datenbank wird vor jedem Test zurückgesetzt

    public function test_user_kann_sitter_suchen(): void
    {
        // ARRANGE: Testdaten anlegen
        $sitter = SitterProfile::factory()->create([
            'city' => 'München',
            'lat' => 48.1351,
            'lng' => 11.5820,
        ]);

        // ACT: HTTP-Request an die API schicken (wie ein echter Browser)
        $response = $this->getJson('/api/sitters/search?city=München');

        // ASSERT: Prüfen ob die Antwort stimmt
        $response->assertStatus(200);
        $response->assertJsonFragment(['city' => 'München']);
    }
}
```

**Was passiert hier:**
1. `ARRANGE` → Testdaten in die DB schreiben (Factories erzeugen Fake-Daten)
2. `ACT` → API-Endpoint aufrufen wie ein echter Request
3. `ASSERT` → Prüfen ob Status 200 zurückkommt und die Daten stimmen

### Tests ausführen

```bash
cd backend
php artisan test                    # alle Tests
php artisan test --filter=Sitter    # nur Sitter-Tests
php artisan test --coverage         # mit Abdeckungsanalyse
```

### Beispiel Unit-Test

```php
// tests/Unit/GeoSearchServiceTest.php

class GeoSearchServiceTest extends TestCase
{
    public function test_radius_berechnung_ist_korrekt(): void
    {
        $service = new GeoSearchService();

        // München → Augsburg sind ~70km
        $distanz = $service->distanzInKm(48.1351, 11.5820, 48.3705, 10.8978);

        $this->assertEqualsWithDelta(70, $distanz, delta: 5); // ±5km Toleranz
    }
}
```

Unit-Tests testen eine einzelne Methode, ohne Datenbank oder HTTP.

---

## Frontend-Tests (Vitest + Vue Test Utils)

Vitest ist ein schnelles Test-Framework für JavaScript, speziell für Vite/Nuxt.

### Wo liegen die Tests?

```
frontend/tests/
├── unit/           → einzelne Komponenten oder Composables testen
│   ├── SearchBar.test.ts
│   └── useSearch.test.ts
└── integration/    → Zusammenspiel mehrerer Komponenten
    └── SearchFlow.test.ts
```

### Beispiel: Komponenten-Test

```typescript
// tests/unit/SearchBar.test.ts
import { mount } from '@vue/test-utils'
import SearchBar from '~/components/search/SearchBar.vue'

describe('SearchBar', () => {
  it('zeigt Fehlermeldung wenn Ort leer ist', async () => {
    const wrapper = mount(SearchBar)

    // Button klicken ohne Ort einzugeben
    await wrapper.find('button[type=submit]').trigger('click')

    // Fehlermeldung muss erscheinen
    expect(wrapper.text()).toContain('Bitte einen Ort eingeben')
  })

  it('sendet Suche mit korrektem Ort ab', async () => {
    const wrapper = mount(SearchBar)

    // Ort eintippen
    await wrapper.find('input[name=city]').setValue('München')
    await wrapper.find('button[type=submit]').trigger('click')

    // Event muss emittiert worden sein
    expect(wrapper.emitted('search')).toBeTruthy()
    expect(wrapper.emitted('search')![0]).toEqual([{ city: 'München' }])
  })
})
```

### Tests ausführen

```bash
cd frontend
npm run test          # einmalig
npm run test:watch    # automatisch bei Dateiänderung (während Entwicklung)
```

---

## CI/CD: Tests in GitHub Actions

Der wichtigste Teil: Tests laufen automatisch bei jedem Push.

```
Push auf main
    ↓
GitHub Actions startet
    ↓
Tests laufen (echte DB, echter HTTP-Request)
    ↓
✅ Alle grün → Deployment startet
❌ Ein Test rot → Deployment wird gestoppt, du bekommst E-Mail
```

Das verhindert, dass kaputter Code live geht.
