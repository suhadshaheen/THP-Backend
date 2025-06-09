<?php
namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
public function store(Request $request)
{
    $validated = $request->validate([
        'bidId' => 'required|exists:bids,id',
        'freelancerId' => 'required|exists:users,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string',
    ]);

    $existingReview = Review::where('bid_id', $validated['bidId'])
        ->where('reviewer_id', auth()->id())
        ->first();

    if ($existingReview) {
        return response()->json(['message' => 'You have already rated this offer.'], 400);
    }

    $review = Review::create([
        'bid_id' => $validated['bidId'],
        'reviewer_id' => auth()->id(),
        'freelancer_id' => $validated['freelancerId'],
        'rating' => $validated['rating'],
        'review_text' => $validated['comment'],
    ]);

    return response()->json(['message' => 'The rating has been saved successfully', 'review' => $review], 201);
}


    public function showByBid($bidId)
    {
        $review = Review::where('bid_id', $bidId)->first();

        if (!$review) {
            return response()->json(['message' => 'There is no rating for this bid'], 404);
        }

        return response()->json($review);
    }

    public function getFreelancerRating($freelancerId)
    {
        $reviews = Review::where('freelancer_id', $freelancerId)->get();

        if ($reviews->isEmpty()) {
            return response()->json([
                'average_rating' => null,
                'rating_count' => 0
            ]);
        }

        $average = round($reviews->avg('rating'), 1);
        $count = $reviews->count();

        return response()->json([
            'average_rating' => $average,
            'rating_count' => $count
        ]);
    }
    public function topRated()
    {
        $topFreelancers = Review::with('freelancer')
            ->selectRaw('freelancer_id, AVG(rating) as avg_rating, COUNT(*) as total_reviews')
            ->groupBy('freelancer_id')
            ->orderByDesc('avg_rating')
            ->take(3)
            ->get();

        $data = $topFreelancers->map(function ($item) {
            $latestReview = Review::where('freelancer_id', $item->freelancer_id)
                ->latest()
                ->with('reviewer')
                ->first();

            return [
                'freelancer_id' => $item->freelancer_id,
                'name' => $item->freelancer?->username ?? 'Unknown',
                'rating' => round($item->avg_rating, 1),
                'total_reviews' => $item->total_reviews,
                'comment' => $latestReview?->review_text,
                'date' => $latestReview?->created_at?->toDateString(),
                'client' => $latestReview?->reviewer?->name ?? null,
            ];
        });

        return response()->json($data);
    }


}
