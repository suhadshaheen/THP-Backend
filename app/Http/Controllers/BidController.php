<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bid;

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
         $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'Freelancer_id' => 'required|exists:users,id',
            'bid_amount' => 'required|integer|min:1',
            'work_time_line' => 'required|string',
            'status' => 'in:pending,accepted,rejected'
        ]);

        $bid = Bid::create($validated);

        return response()->json($bid, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          $bid = Bid::find($id);
        if (!$bid) {
            return response()->json(['message' => 'Bid not found'], 404);
        }
        return response()->json($bid);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
          $bid = Bid::find($id);
        if (!$bid) {
            return response()->json(['message' => 'Bid not found'], 404);
        }

        $validated = $request->validate([
            'bid_amount' => 'sometimes|integer|min:1',
            'work_time_line' => 'sometimes|string',
            'status' => 'sometimes|in:pending,accepted,rejected'
        ]);

        $bid->update($validated);

        return response()->json($bid);
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Request $request, $id)
{
    $bid = Bid::find($id);
    if (!$bid) {
        return response()->json(['message' => 'Bid not found'], 404);
    }

    $jobOwnerId = $bid->job->user_id;

    if ($request->input('job_owner_id') != $jobOwnerId) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $bid->delete();

    return response()->json(['message' => 'Bid deleted']);
}

public function getBidsForJob($jobId)
{
    $bids = Bid::where('job_id', $jobId)->get();

    return response()->json($bids);
}

}
