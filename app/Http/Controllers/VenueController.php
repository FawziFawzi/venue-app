<?php

namespace App\Http\Controllers;

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
        $venues = Venue::all();
        return venueResource::collection($venues);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validating credentials
        $request->validate([
            'name' => 'required|string|max:255|unique:venues',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
        ]);

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
    public function update(Request $request, string $id)
    {
        //Validating credentials
        $credentials = $request->validate([
            'name' => 'required|string|max:255|unique:venues',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
        ]);

        $venue = Venue::findOrFail($id);

        //Updating
        $venue->name = $credentials['name'];
        $venue->location = $credentials['location'];
        $venue->capacity = $credentials['capacity'];
        $venue->save();

        return response()->json([
            'message' => 'Venue updated successfully',
            'venue' => new VenueResource($venue)
        ]);

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
        ]);
    }
}
