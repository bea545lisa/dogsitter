<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SitterProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'bio', 'city', 'zip', 'lat', 'lng',
        'care_type', 'price_halfday', 'price_fullday', 'dog_sizes', 'is_active',
    ];

    protected $appends = ['average_rating'];

    protected $casts = [
        'dog_sizes' => 'array',
        'lat' => 'float',
        'lng' => 'float',
        'price_halfday' => 'float',
        'price_fullday' => 'float',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'sitter_id', 'user_id');
    }

    // Durchschnittsbewertung als berechnetes Attribut
    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    // Radius-Suche: gibt alle Sitter innerhalb von $km Kilometern zurück
    public function scopeNearby($query, float $lat, float $lng, int $km = 30)
    {
        // Haversine-Formel: berechnet Luftlinienabstand aus lat/lng
        return $query->selectRaw("*, (
            6371 * acos(
                cos(radians(?)) * cos(radians(lat)) *
                cos(radians(lng) - radians(?)) +
                sin(radians(?)) * sin(radians(lat))
            )
        ) AS distance", [$lat, $lng, $lat])
        ->having('distance', '<=', $km)
        ->orderBy('distance');
    }
}
