<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\DB;

class GoogleController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        $redirectUrl = $request->input('redirect');
        // Ensure the redirect URL is safe and matches your app's allowed URLs
        $state = base64_encode(json_encode(['redirectUrl' => $redirectUrl, 'csrfToken' => csrf_token()]));
        return Socialite::driver('google')
            ->with(['state' => $state])
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $state = json_decode(base64_decode($request->input('state')), true);
        $redirectUrl = $state['redirectUrl'] ?? url('/default-redirect-url');

        try {
            $user = Socialite::driver('google')->stateless()->user();
            $redirectUrlWithToken = $redirectUrl . '?token=' . $user->token;
            return redirect($redirectUrlWithToken);
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors(['msg' => 'Failed to authenticate with Google. Please try again.']);
        }
    }

    public function validateToken(Request $request)
    {
        $validateData = $request->validate([
            'token' => 'required',
        ]);
        // Validates the token from the request
        try {
            $userData = Socialite::driver('google')->stateless()->userFromToken($validateData['token']);
            $email = $userData['email'];
            $user = DB::table('users')->where('email', $email)->first();


            // Process the user details returned from Google
            return response()->json($user);
        } catch (Exception $e) {
            // Handle the case where token is invalid or expired
            return response()->json(['error' => 'Invalid or expired token.'], 401);
        }
    }
}
