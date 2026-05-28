<?php

namespace Database\Seeders;

use App\Models\SitterProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SitterSeeder extends Seeder
{
    public function run(): void
    {
        $sitters = [
            [
                'name' => 'Maria Huber',
                'email' => 'maria@example.com',
                'city' => 'München',
                'lat' => 48.1351, 'lng' => 11.5820,
                'bio' => 'Ich betreue Hunde seit 10 Jahren mit viel Liebe und Geduld. Großer Garten vorhanden.',
                'price_halfday' => 25, 'price_fullday' => 45,
                'care_type' => 'private',
                'dog_sizes' => ['small', 'medium'],
            ],
            [
                'name' => 'Thomas Berger',
                'email' => 'thomas@example.com',
                'city' => 'München',
                'lat' => 48.1500, 'lng' => 11.5500,
                'bio' => 'Aktiver Hundebesitzer, kenne alle Herausforderungen. Tägliche Spaziergänge garantiert.',
                'price_halfday' => 20, 'price_fullday' => 38,
                'care_type' => 'private',
                'dog_sizes' => ['small', 'medium', 'large'],
            ],
            [
                'name' => 'Sandra Koch',
                'email' => 'sandra@example.com',
                'city' => 'Augsburg',
                'lat' => 48.3705, 'lng' => 10.8978,
                'bio' => 'Professionelle Hundebetreuerin mit eigenem Zwinger. Platz für bis zu 4 Hunde.',
                'price_halfday' => 30, 'price_fullday' => 55,
                'care_type' => 'pension',
                'dog_sizes' => ['small', 'medium', 'large'],
            ],
            [
                'name' => 'Klaus Maier',
                'email' => 'klaus@example.com',
                'city' => 'Rosenheim',
                'lat' => 47.8561, 'lng' => 12.1289,
                'bio' => 'Rentner mit viel Zeit und einem großen Herz für Tiere. Ruhige Umgebung am Stadtrand.',
                'price_halfday' => 18, 'price_fullday' => 32,
                'care_type' => 'private',
                'dog_sizes' => ['small'],
            ],
        ];

        foreach ($sitters as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'sitter',
            ]);

            SitterProfile::create([
                'user_id' => $user->id,
                'bio' => $data['bio'],
                'city' => $data['city'],
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'care_type' => $data['care_type'],
                'price_halfday' => $data['price_halfday'],
                'price_fullday' => $data['price_fullday'],
                'dog_sizes' => $data['dog_sizes'],
                'is_active' => true,
            ]);
        }
    }
}
