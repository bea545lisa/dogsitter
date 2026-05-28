<?php

namespace App\Http\Controllers;

use App\Models\SitterProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SitterController extends Controller
{
    public function show(User $sitter): JsonResponse
    {
        $profile = $sitter->sitterProfile()->firstOrFail();

        return response()->json($profile->load(['user', 'reviews.author']));
    }

    public function store(Request $request): JsonResponse
    {
        abort_if($request->user()->sitterProfile()->exists(), 409, 'Profil existiert bereits.');

        $data = $request->validate([
            'bio' => 'nullable|string|max:2000',
            'city' => 'required|string|max:100',
            'zip' => 'nullable|string|max:10',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'care_type' => 'required|in:private,pension',
            'price_halfday' => 'nullable|numeric|min:0',
            'price_fullday' => 'nullable|numeric|min:0',
            'dog_sizes' => 'nullable|array',
            'dog_sizes.*' => 'in:small,medium,large',
        ]);

        $profile = $request->user()->sitterProfile()->create($data);

        return response()->json($profile, 201);
    }

    public function update(Request $request): JsonResponse
    {
        $profile = $request->user()->sitterProfile()->firstOrFail();

        $data = $request->validate([
            'bio' => 'nullable|string|max:2000',
            'city' => 'sometimes|string|max:100',
            'zip' => 'nullable|string|max:10',
            'lat' => 'sometimes|numeric',
            'lng' => 'sometimes|numeric',
            'care_type' => 'sometimes|in:private,pension',
            'price_halfday' => 'nullable|numeric|min:0',
            'price_fullday' => 'nullable|numeric|min:0',
            'dog_sizes' => 'nullable|array',
            'dog_sizes.*' => 'in:small,medium,large',
            'is_active' => 'boolean',
        ]);

        $profile->update($data);

        return response()->json($profile);
    }
}
