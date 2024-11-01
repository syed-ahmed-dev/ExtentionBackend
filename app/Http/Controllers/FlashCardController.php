<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\FlashCard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\FlashcardsResource;

class FlashCardController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;
        $collectionIds = Collection::where('user_id', $userId)->pluck('id');
        $flashcards = FlashCard::whereIn('collection_id', $collectionIds)->get();
        return $this->sendResponse(true, Response::HTTP_OK, 'FlashCard list.', $flashcards);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'collection_id' => 'required|exists:collections,id',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors()->first(), 'Validation Error');
        }

        $flashcard = FlashCard::create($validator->validated());
        return $this->sendResponse(true, Response::HTTP_OK, 'FlashCard create successfully.', $flashcard);

    }

    public function show(FlashCard $flashcard)
    {
        return $this->sendResponse(true, Response::HTTP_OK, 'FlashCard List.', $flashcard);
    }

    public function update(Request $request, FlashCard $flashcard)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|required|string|max:255',
            'company' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'notes' => 'nullable|string',
            'collection_id' => 'sometimes|required|exists:collections,id',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors()->first(), 'Validation Error');
        }

        $flashcard->update($validator);
        return $this->sendResponse(true, Response::HTTP_OK, 'FlashCard update successfully.', $flashcard);
    }

    public function destroy(FlashCard $flashcard, $id)
    {
        $userId = auth()->id();

        $flashcard = FlashCard::with('collection')->find($id);

        if (!$flashcard || $flashcard->collection->user_id !== $userId) {
            return $this->sendResponse(false, Response::HTTP_BAD_REQUEST, 'Not Found Or Unauthorized.', null);
        }

        $flashcard->delete();
        return $this->sendResponse(true, Response::HTTP_NO_CONTENT, 'FlashCard deleted successfully.', null);
    }

}
