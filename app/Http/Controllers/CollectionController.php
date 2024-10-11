<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::all();
        return response()->json($collections, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:collection',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors()->first(), 'Validation Error');
        }

        $collection = Collection::create($validator);
        return response()->json($collection, Response::HTTP_CREATED);
    }

    public function show(Collection $collection)
    {
        return response()->json($collection, Response::HTTP_OK);
    }

    public function update(Request $request, Collection $collection)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors()->first(), 'Validation Error');
        }

        $collection->update($validator);
        return response()->json($collection, Response::HTTP_OK);
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
