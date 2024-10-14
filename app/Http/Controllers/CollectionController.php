<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Fetch collections that belong to the authenticated user
        $collections = Collection::where('user_id', $userId)->get();

        return $this->sendResponse(true, Response::HTTP_OK, 'Collection List.', $collections);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:collections',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors()->first(), 'Validation Error');
        }
        $userId = auth()->user()->id;

        $data = [
            'name' => $request->name,
            'user_id' => $userId,
        ];

        $collection = Collection::create($data);
        return $this->sendResponse(true, Response::HTTP_CREATED, 'Collection create successfully.', null);

    }

    public function show(Collection $collection)
    {
        $userId = auth()->id();

        if ($collection->user_id !== $userId) {
            return $this->sendResponse(false, Response::HTTP_FORBIDDEN, 'Unauthorized access.', null);
        }

        return $this->sendResponse(true, Response::HTTP_OK, 'Collection retrieved successfully.', $collection);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors()->first(), 'Validation Error');
        }

        $userId = auth()->id();

        $collection = Collection::where('user_id', $userId)->first();

        if (!$collection) {
            return $this->sendResponse(false, Response::HTTP_NOT_FOUND, 'Collection not found.', null);
        }

        $collection->update($validator->validated());

        return $this->sendResponse(true, Response::HTTP_OK, 'Collection updated successfully.', null);
    }

    public function destroy(Request $request, $id)
    {

        $collection = Collection::where('id', $id)->first();

        $userId = auth()->id();
        if ($collection->user_id !== $userId) {
            return $this->sendResponse(false, Response::HTTP_FORBIDDEN, 'Unauthorized action.', null);
        }

        $collection->delete();
        return $this->sendResponse(true, Response::HTTP_NO_CONTENT, 'Collection deleted successfully.', null);

    }

}
