<?php

namespace App\Http\Controllers;

use App\Models\FlashCard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class FlashCardController extends Controller
{
    public function index()
    {
        $flashcards = FlashCard::all();
        return response()->json($flashcards, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'collection_id' => 'required|exists:collection,id',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors()->first(), 'Validation Error');
        }

        $flashcard = FlashCard::create($validator);
        return response()->json($flashcard, Response::HTTP_CREATED);
    }

    public function show(FlashCard $flashcard)
    {
        return response()->json($flashcard, Response::HTTP_OK);
    }

    public function update(Request $request, FlashCard $flashcard)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|required|string|max:255',
            'company' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'notes' => 'nullable|string',
            'collection_id' => 'sometimes|required|exists:collection,id',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors()->first(), 'Validation Error');
        }

        $flashcard->update($validator);
        return response()->json($flashcard, Response::HTTP_OK);
    }

    public function destroy(FlashCard $flashcard)
    {
        $flashcard->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
