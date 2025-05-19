<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
   public function sendResetLinkEmail(Request $request){
       $request->validate(['email' => 'required|email']);
       $status = Password::sendResetLink(
           $request->only('email')
       );
       return $status === Password::RESET_LINK_SENT
           ? response()->json(['message' => 'Rest link sent'])
           : response()->json(['error' => 'Email not found'], 400);
   }
}
