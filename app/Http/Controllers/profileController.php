<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // ProfileController.php
public function updatePhoto(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $profile = Profile::firstOrCreate(['user_id' => $request->user_id]);

    if ($profile->User_photo) {
        Storage::disk('public')->delete($profile->User_photo);
    }

    $photoPath = $request->file('photo')->store('profile_photos', 'public');
    $profile->User_photo = $photoPath;
    $profile->save();

    return response()->json([
        'message' => 'Profile photo updated successfully',
        'photo' => $profile->User_photo
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
          'skills' => 'nullable|string|max:500',
        ]);

        $profile = Profile::firstOrCreate(['user_id' => $request->user_id]);
        $profile->skills = $request->skills;
        $profile->save();

        return response()->json([
            'message' => 'Profile skills updated successfully',
            'skills' => $profile->skills
        ]);
    }

    public function updateSocialLinks(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'whatsappNumber' => 'nullable|string|max:20',
            'InstagramLink' => 'nullable|url|max:255',
            'FacebookLink' => 'nullable|url|max:255',
        ]);

        $profile = Profile::firstOrCreate(['user_id' => $request->user_id]);

        $profile->whatsappNumber = $request->whatsappNumber;
        $profile->InstagramLink = $request->InstagramLink;
        $profile->FacebookLink = $request->FacebookLink;
        $profile->save();

        return response()->json([
            'message' => 'Social links updated successfully',
            'whatsappNumber' => $profile->whatsappNumber,
            'InstagramLink' => $profile->InstagramLink,
            'FacebookLink' => $profile->FacebookLink,
        ]);
    }
}
