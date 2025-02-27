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
    {;
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
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VenueRequest $request, int $id)
    {
        // Retrieve the venue
        $venue = Venue::findOrFail($id);

        //Updating
        $venue->name = $request->name;
        $venue->location = $request->location;
        $venue->capacity = $request->capacity;
        $venue->save();

        return response()->json([
            'message' => 'Venue updated successfully',
            'venue' => new VenueResource($venue)
        ],200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //Deleting Venue
        $venue = Venue::find($id);
        $venue->delete();
        return response()->json([
            'message' => 'Venue deleted successfully',
        ],200);
    }
}
