<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::query();
    if ($request->filled('category')) {
    $query->where('category', $request->query('category'));
    }

    if ($request->filled('budget')) {
    $query->where('budget', '<=', $request->query('budget'));
    }

    if ($request->filled('location')) {
    $query->where('location', $request->query('location'));
    }

    if ($request->filled('search')) {
    $searchTerm = $request->query('search');
    $query->where(function ($q) use ($searchTerm) {
        $q->where('title', 'like', "%$searchTerm%")
          ->orWhere('description', 'like', "%$searchTerm%")
          ->orWhere('location', 'like', "%$searchTerm%")
          ->orWhere('budget', 'like', "%$searchTerm%");
        });
     }


        $jobs = $query->get();

        return response()->json($jobs);
    }

    public function show($id)
    {
        $job = Job::find($id);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }
        return response()->json($job);
    }

    public function updateStatus(Request $request, $id)
    {
        $job = Job::find($id);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $job->status = $request->status;
        $job->save();

        return response()->json($job);
    }
}
