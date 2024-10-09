<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectionController extends Controller
{
    // Get all collections
    public function index()
    {
        $collections = Collection::all();
        return response()->json($collections, Response::HTTP_OK);
    }

    // Store a new collection
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:collection',
        ]);

        $collection = Collection::create($validated);
        return response()->json($collection, Response::HTTP_CREATED);
    }

    // Get a specific collection
    public function show(Collection $collection)
    {
        return response()->json($collection, Response::HTTP_OK);
    }

    // Update a collection
    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        $collection->update($validated);
        return response()->json($collection, Response::HTTP_OK);
    }

    // Delete a collection
    public function destroy(Collection $collection)
    {
        $collection->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
