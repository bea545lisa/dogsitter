<?php

namespace App\Http\Controllers;

use App\Models\SitterProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'lat' => 'required_without:city|numeric',
            'lng' => 'required_without:city|numeric',
            'city' => 'required_without:lat|string',
            'radius' => 'integer|min:1|max:200',
            'dog_size' => 'in:small,medium,large',
            'care_type' => 'in:private,pension',
        ]);

        $radius = $request->integer('radius', 30);

        $query = SitterProfile::with('user')
            ->where('is_active', true);

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        } elseif ($request->filled('lat')) {
            $query->nearby($request->float('lat'), $request->float('lng'), $radius);
        }

        if ($request->filled('dog_size')) {
            $query->whereJsonContains('dog_sizes', $request->dog_size);
        }

        if ($request->filled('care_type')) {
            $query->where('care_type', $request->care_type);
        }

        $sitters = $query->get();

        return response()->json($sitters);
    }
}
