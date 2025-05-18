<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bid;
use Illuminate\Support\Facades\Auth;
class BidsController extends Controller
{

public function store(Request $request)
{
    $request->validate([
        'job_id' => 'required|exists:jobs,id',
        'bid_amount' => 'required|integer|min:1',
        'work_time_line' => 'required|string|max:255',
    ]);

    $bid = Bid::create([
        'job_id' => $request->job_id,
        'Freelancer_id' => Auth::id(), // المستخدم المسجل حاليًا
        'bid_amount' => $request->bid_amount,
        'work_time_line' => $request->work_time_line,
        'Bid_Date' => now(),
    ]);

    return response()->json([
        'message' => 'Bid submitted successfully.',
        'data' => $bid
    ], 201);
}
public function index($jobId)
{
    $bids = Bid::with('freelancer')->where('job_id', $jobId)->get();

    return response()->json([
        'job_id' => $jobId,
        'bids' => $bids,
    ]);
}
public function destroy($id)
{
    $bid = Bid::findOrFail($id);

    if ($bid->Freelancer_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $bid->delete();

    return response()->json(['message' => 'Bid deleted successfully']);
}

}
