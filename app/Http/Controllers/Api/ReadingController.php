<?php

namespace App\Http\Controllers\Api;

use App\Models\Reading;
use Illuminate\Http\Request;
use App\Http\Requests\ReadingRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReadingResource;

class ReadingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $readings = Reading::paginate();

        return ReadingResource::collection($readings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReadingRequest $request): Reading
    {
        return Reading::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Reading $reading): Reading
    {
        return $reading;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReadingRequest $request, Reading $reading): Reading
    {
        $reading->update($request->validated());

        return $reading;
    }

    public function destroy(Reading $reading): Response
    {
        $reading->delete();

        return response()->noContent();
    }
}
