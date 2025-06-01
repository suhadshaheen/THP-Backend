<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'nullable|in:pending,in_progress,completed',
            'location' => 'required|string',
            'category' => 'required|string',
            'job_requirements' => 'nullable|string',
            'deadline' => 'nullable|date',
            'posting_date' => 'nullable|date',
            'job_owner_id' => 'required|exists:users,id',
            'JobPhoto' => 'nullable|string',
            'budget' => 'required|numeric',
            'attempts' => 'nullable|integer',
            'available_at' => 'nullable|date',
            'experience' => 'nullable|string',
            'work_level' => 'nullable|string',
        ]);

        $job = Job::create($validated);

        return response()->json($job, 201);
    } 


    public function update(Request $request, $id)
    {
        $job = Job::find($id);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'nullable|in:pending,in_progress,completed',
            'location' => 'required|string',
            'category' => 'required|string',
            'job_requirements' => 'nullable|string',
            'deadline' => 'nullable|date',
            'posting_date' => 'nullable|date',
            'job_owner_id' => 'required|exists:users,id',
            'JobPhoto' => 'nullable|string',
            'budget' => 'required|numeric',
            'experience' => 'nullable|string',
            'work_level' => 'nullable|string',
        ]);

        $job->update($validated);

        return response()->json($job);
    }
  

    public function destroy($id)
    {
        $job = Job::find($id);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $job->delete();

        return response()->json(['message' => 'Job deleted successfully']);
    }

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

    public function myJobs(Request $request)
    {
        $user = $request->user(); 
        $jobs = Job::where('job_owner_id', $user->id)->get();
        return response()->json($jobs);
    }

    // public function updateStatus(Request $request, $id)
    // {
    //     $job = Job::find($id);
    //     if (!$job) {
    //         return response()->json(['message' => 'Job not found'], 404);
    //     }

    //     $request->validate([
    //         'status' => 'required|in:pending,in_progress,completed',
    //     ]);

    //     $job->status = $request->status;
    //     $job->save();

    //     return response()->json($job);
    // }
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
