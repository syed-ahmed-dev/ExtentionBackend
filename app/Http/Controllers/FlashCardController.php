<?php

namespace App\Http\Controllers;

use App\Models\FlashCard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FlashCardController extends Controller
{
    // Get all flashcards
    public function index()
    {
        $flashcards = FlashCard::all();
        return response()->json($flashcards, Response::HTTP_OK);
    }

    // Store a new flashcard
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'collection_id' => 'required|exists:collection,id',
        ]);

        $flashcard = FlashCard::create($validated);
        return response()->json($flashcard, Response::HTTP_CREATED);
    }

    // Get a specific flashcard
    public function show(FlashCard $flashcard)
    {
        return response()->json($flashcard, Response::HTTP_OK);
    }

    // Update a flashcard
    public function update(Request $request, FlashCard $flashcard)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|required|string|max:255',
            'company' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'notes' => 'nullable|string',
            'collection_id' => 'sometimes|required|exists:collection,id',
        ]);

        $flashcard->update($validated);
        return response()->json($flashcard, Response::HTTP_OK);
    }

    // Delete a flashcard
    public function destroy(FlashCard $flashcard)
    {
        $flashcard->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
