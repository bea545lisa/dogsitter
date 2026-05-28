<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Hundebesitzer sehen ihre eigenen Buchungsanfragen
        // Sitter sehen eingehende Anfragen
        $bookings = Booking::with(['owner', 'sitter', 'review'])
            ->where(function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                  ->orWhere('sitter_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json($bookings);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'sitter_id' => 'required|exists:users,id',
            'dog_name' => 'required|string|max:100',
            'dog_size' => 'required|in:small,medium,large',
            'from_date' => 'required|date|after_or_equal:today',
            'to_date' => 'required|date|after_or_equal:from_date',
            'message' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::create([
            ...$data,
            'owner_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        return response()->json($booking->load(['owner', 'sitter']), 201);
    }

    public function confirm(Request $request, Booking $booking): JsonResponse
    {
        $this->authorizeSitter($request, $booking);

        $booking->update(['status' => 'confirmed']);

        return response()->json($booking);
    }

    public function reject(Request $request, Booking $booking): JsonResponse
    {
        $this->authorizeSitter($request, $booking);

        $booking->update(['status' => 'rejected']);

        return response()->json($booking);
    }

    private function authorizeSitter(Request $request, Booking $booking): void
    {
        abort_unless($booking->sitter_id === $request->user()->id, 403);
    }
}
