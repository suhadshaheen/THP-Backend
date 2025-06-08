<?php

namespace App\Http\Controllers;

use App\Models\JobPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobPhotoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $storedPhotos = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('job_photos', 'public');

            $photo = JobPhoto::create([
                'job_id' => $request->job_id,
                'photo_path' => $path
            ]);

            $storedPhotos[] = $photo;
        }

        return response()->json(['message' => 'Images uploaded successfully.', 'photos' => $storedPhotos], 201);
    }

    // one job photos
    public function index($jobId)
    {
        $photos = JobPhoto::where('job_id', $jobId)->get();
        return response()->json($photos);
    }

    public function destroy($id)
    {
        $photo = JobPhoto::findOrFail($id);

        if (Storage::disk('public')->exists($photo->photo_path)) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        $photo->delete();

        return response()->json(['message' => 'Photo deleted successfully.']);
    }
}
