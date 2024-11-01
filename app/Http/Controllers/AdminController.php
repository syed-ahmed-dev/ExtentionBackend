<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\FlashCard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{

    public function getUser(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }

        $query->orderBy('created_at', 'DESC');

        $users = $query->paginate($request->input('per_page', 10));

        return $this->sendResponse(true, Response::HTTP_OK, 'User List.', $users);
    }

    public function allFlash(Request $request)
    {
        $query = FlashCard::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('company', 'like', '%' . $search . '%');
        }
        $query->orderBy('created_at', 'DESC');
        $flashCard = $query->paginate($request->input('per_page', 10));

        return $this->sendResponse(true, Response::HTTP_OK, 'FlashCard List.', $flashCard);

    }

    public function allCollection(Request $request)
    {
        $query = Collection::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('user_id', 'like', '%' . $search . '%');
        }
        $query->orderBy('created_at', 'DESC');

        $collection = $query->paginate($request->input('per_page', 10));

        return $this->sendResponse(true, Response::HTTP_OK, 'Collection List.', $collection);

    }

    public function collectionWithFlashcard(Request $request)
    {
        $query = Collection::with(['flashcards' => function ($q) use ($request) {
            if ($request->has('name')) {
                $q->where('name', 'like', '%' . $request->input('name') . '%');
            }

            if ($request->has('company')) {
                $q->where('company', 'like', '%' . $request->input('company') . '%');
            }

            if ($request->has('title')) {
                $q->where('title', 'like', '%' . $request->input('title') . '%');
            }

            if ($request->has('notes')) {
                $q->where('notes', 'like', '%' . $request->input('notes') . '%');
            }
        }]);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->has('collection_name')) {
            $query->where('name', 'like', '%' . $request->input('collection_name') . '%');
        }

        $collections = $query->get();

        return $this->sendResponse(true, Response::HTTP_OK, 'Collection List With FlashCards.', $collections);
    }

}
