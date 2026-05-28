<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(User $sitter): JsonResponse
    {
        $reviews = $sitter->receivedReviews()
            ->with('author')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($reviews);
    }

    public function store(Request $request, Booking $booking): JsonResponse
    {
        // Nur der Hundebesitzer dieser Buchung darf eine Bewertung schreiben
        abort_unless($booking->owner_id === $request->user()->id, 403);

        // Buchung muss abgeschlossen sein
        abort_unless($booking->status === 'confirmed', 422, 'Nur bestätigte Buchungen können bewertet werden.');

        // Keine Doppelbewertung
        abort_if($booking->review()->exists(), 409, 'Diese Buchung wurde bereits bewertet.');

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = $booking->review()->create([
            ...$data,
            'author_id' => $request->user()->id,
            'sitter_id' => $booking->sitter_id,
        ]);

        return response()->json($review->load('author'), 201);
    }
}
