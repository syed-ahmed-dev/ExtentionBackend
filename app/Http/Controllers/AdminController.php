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

}
