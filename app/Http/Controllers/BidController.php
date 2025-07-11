<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bid;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{

    public function index()
    {
        $userId = Auth::user()->id;
        $bids = Bid::with('job')->where('Freelancer_id', $userId)->orderBy('created_at', 'desc')->get();


        return response()->json($bids);
    }

  public function store(Request $request)
{
    $userId = Auth::user()->id;

    $validated = $request->validate([
        'job_id' => 'required|exists:jobs,id',
        'bid_amount' => 'required|integer|min:1',
        'work_time_line' => 'required|string',
        'status' => 'in:pending,accepted,rejected'

    ]);
    $existingBid = Bid::where('Freelancer_id', $userId)->where('job_id', $validated['job_id'])->first();

    if ($existingBid) {
        return response()->json([
            'message' => 'You have already placed a bid on this job.'
        ], 409);
    }

    $validated['Freelancer_id'] = $userId;

    $bid = Bid::create($validated);

    return response()->json($bid, 201);
}

    public function show(string $id)
    {
        $bid = Bid::find($id);
        if (!$bid) {
            return response()->json(['message' => 'Bid not found'], 404);
        }
        if ($bid->Freelancer_id != Auth::user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($bid);
    }


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
        $job = Job::find($jobId);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        if ($job->job_owner_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $bids = Bid::with('freelancer')->where('job_id', $jobId)->get();

        return response()->json($bids);
    }


    public function changeStatus(Request $request, $id)
    {
        $bid = Bid::find($id);
        if (!$bid) {
            return response()->json(['message' => 'Bid not found'], 404);
        }
        $jobOwnerId = $bid->job->job_owner_id;

        if (Auth::id() !== $jobOwnerId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $bid->status = $validated['status'];
        $bid->save();
        return response()->json(['message' => 'Bid status updated', 'bid' => $bid]);
    }



}
