<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    // artisans
    public function index()
    {
        $artisans = User::whereHas('role', function ($query) {
            $query->where('id', '2')
            ->orWhere('id', '3');
        })->get();

        return response()->json([
            'status' => true,
            'data' => $artisans
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending'
        ]);

        $artisan = User::whereHas('role', function ($query) {
            $query->where('id', '2')
            ->orWhere('id', '3');
        })->findOrFail($id);

        $artisan->status = $request->status;
        $artisan->save();

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $artisan = User::whereHas('role', function ($query) {
            $query->where('id', '2')
            ->orWhere('id', '3');
        })->findOrFail($id);

        $artisan->delete();

        return response()->json([
            'status' => true,
            'message' => 'Artisan deleted successfully'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $results = User::whereHas('role', function ($q) {
            $query->where('id', '2')
            ->orWhere('id', '3');
        })->where(function ($q) use ($query) {
            $q->where('username', 'like', "%$query%");
              
        })->get();

        return response()->json([
            'status' => true,
            'results' => $results
        ]);
    }

    // jobs
    public function listJobs()
    {
        $jobs = Job::all();
        return response()->json($jobs);
    }

    public function showJob($id)
    {
        return response()->json(Job::findOrFail($id));
    }

    public function updateJobStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $job = Job::findOrFail($id);
        $job->status = $request->status;
        $job->save();

        return response()->json(['message' => 'Job status updated']);
    }

    public function deleteJob($id)
    {
        Job::destroy($id);
        return response()->json(['message' => 'Job deleted']);
    }
}
