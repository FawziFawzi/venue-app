<?php

namespace App\Http\Controllers;

use App\Http\Requests\VenueRequest;
use App\Http\Resources\VenueResource;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $venues = Venue::paginate(5);
        return response()->json([
            'venues' => VenueResource::collection($venues)
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VenueRequest $request)
    {
        //Validating credentials
        $request->validated();

        //Storing the venue in database
        $venue = Venue::create([
            'name' => $request->name,
            'location' => $request->location,
            'capacity' => $request->capacity,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Venue created successfully',
            'venue' => new VenueResource($venue)
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(VenueRequest $request, string $id)
    {
        //Validating credentials
        $credentials = $request->validated();

        $venue = Venue::findOrFail($id);

        //Updating
        $venue->name = $credentials['name'];
        $venue->location = $credentials['location'];
        $venue->capacity = $credentials['capacity'];
        $venue->save();

        return response()->json([
            'message' => 'Venue updated successfully',
            'venue' => new VenueResource($venue)
        ],200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        //Deleting Venue
        Venue::destroy($id);

        return response()->json([
            'message' => 'Venue deleted successfully',
        ],200);
    }
}
