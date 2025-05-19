<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Update Profile Photo
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        $profile = $user->profile ?? $user->profile()->create();

        $photoPath = $request->file('photo')->store('profile_photos', 'public');

       
        if ($profile->photo) {
            Storage::disk('public')->delete($profile->photo);
        }

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
            'bio' => 'required|string|max:500',
        ]);

        $user = auth()->user();
        $profile = $user->profile ?? $user->profile()->create();
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
            'skills' => 'required|string|max:500',
        ]);

        $user = auth()->user();
        $profile = $user->profile ?? $user->profile()->create();
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
//         'instagram_link' => 'required|url|max:255',
//     ]);

//     $user = auth()->user();
//     $profile = $user->profile ?? $user->profile()->create();
//     $profile->instagram_link = $request->instagram_link;
//     $profile->save();

//     return response()->json([
//         'message' => 'Instagram link updated successfully',
//         'instagram_link' => $profile->instagram_link
//     ]);
// }


 }
