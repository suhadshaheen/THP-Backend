<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $profile = Profile::firstOrCreate(['user_id' => $request->user_id]);

        if ($profile->photo) {
            Storage::disk('public')->delete($profile->photo);
        }

        $photoPath = $request->file('photo')->store('profile_photos', 'public');
        $profile->photo = $photoPath;
        $profile->save();

        return response()->json([
            'message' => 'Profile photo updated successfully',
            'photo' => $profile->photo
        ]);
    }

  
    public function updateBio(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bio' => 'required|string|max:500',
        ]);

        $profile = Profile::firstOrCreate(['user_id' => $request->user_id]);
        $profile->bio = $request->bio;
        $profile->save();

        return response()->json([
            'message' => 'Profile bio updated successfully',
            'bio' => $profile->bio
        ]);
    }

    public function updateSkills(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'skills' => 'required|string|max:500',
        ]);

        $profile = Profile::firstOrCreate(['user_id' => $request->user_id]);
        $profile->skills = $request->skills;
        $profile->save();

        return response()->json([
            'message' => 'Profile skills updated successfully',
            'skills' => $profile->skills
        ]);
    }

    // public function updateInstagram(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'instagram_link' => 'required|url|max:255',
    //     ]);

    //     $profile = Profile::firstOrCreate(['user_id' => $request->user_id]);
    //     $profile->instagram_link = $request->instagram_link;
    //     $profile->save();

    //     return response()->json([
    //         'message' => 'Instagram link updated successfully',
    //         'instagram_link' => $profile->instagram_link
    //     ]);
    // }
}
