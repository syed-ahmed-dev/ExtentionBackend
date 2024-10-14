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
        return $this->sendResponse(true, Response::HTTP_OK, 'Collection List.', $collections);
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
        return $this->sendResponse(true, Response::HTTP_CREATED, 'Collection create successfully.', null);

    }

    public function show(Collection $collection)
    {
        return response()->json($collection, Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors()->first(), 'Validation Error');
        }
        $userId = auth()->user()->id;

        $collection = Collection::where('user_id',$userId)->first();

        $collection->update($validator);
        return $this->sendResponse(true, Response::HTTP_OK, 'Collection update successfully.', null);
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        return $this->sendResponse(true, Response::HTTP_NO_CONTENT, 'Collection delete successfully.', null);
    }
}
