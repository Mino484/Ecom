<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Base_Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class LoginController extends Base_Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user(); // Fetch the authenticated user
            $accessToken = $request->user()->createToken('Personal Access Token')->accessToken ;
            $user['accessToken'] = $accessToken;
            return $this->sendResponse($user, 'user');
        }
        return $this->sendError(['error' => 'Unauthorised']);
    }
/*{
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $user = Auth::user(); // Fetch the authenticated user
        $accessToken = $user->createToken('Personal Access Token')->accessToken; // Generate the token
        $user['accessToken'] = $accessToken;
        return $this->sendResponse($user, 'user');
    }
    return $this->sendError(['error' => 'Unauthorised']);
}*/







    public function userInfo()
    {
        $user = Auth::user(); // Fetch the authenticated user


                return response()->json(['user' => $user], 200);
    }
}
