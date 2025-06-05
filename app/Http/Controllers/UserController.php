<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\JWTSubject;


class UserController extends Controller
{
    public function index(){
        return response()->json(User::all());
    }
     public function show($id){
        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => 'User not found.'], 404);
        }
        $user->load('profile');//suhad
        return response()->json($user);
    }
    public function update(Request $request,$id){
        $user = User::find ($id);
        if(!$user){
            return response()->json(['message' => 'User not found.'], 404);
        }
        $validatedData = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            // 'password' => 'required',
            'username' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'country' => 'required',
            'role',

        ]);
        $user->update($validatedData);
        return response()->json(['message' => 'User updated successfully.'], 200);
    }
    public function destroy($id){
        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => 'User not found.'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.'], 200);

    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
    public function username()
    {
        return 'username';
    }
    public function getFreelancerRating($freelancerId)
    {
        $user = User::find($freelancerId);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $ratings = $user->reviewsReceived()->pluck('rating');

        if ($ratings->isEmpty()) {
            return response()->json([
                'averageRating' => 0,
                'ratingCount' => 0
            ]);
        }

        return response()->json([
            'averageRating' => round($ratings->avg(), 1),
            'ratingCount' => $ratings->count()
        ]);
    }


}
