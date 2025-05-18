<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bid; // Assuming you have a Bid model

class BidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all bids from the database
        $bids = Bid::all();

        // Return the bids as a JSON response
        return response()->json($bids);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
