<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Base_Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class Register_Controller extends Base_Controller{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|unique:users,phone_number',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if the email already exists
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['error' => 'Email already exists.'], 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'=> $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
        ]);

        return response()->json(['user' => $user, 'message' => 'User registered successfully.'], 201);
    }

    public function updateProfile(Request $request)
{
    $user = Auth::user();
    $validator = Validator::make($request->all(), [
        'first_name' => 'string|max:255',
        'last_name' => 'string|max:255',
        'location' => 'string|nullable',
        'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }


    if ($request->has('first_name')) {
        $user->first_name = $request->first_name;
    }
    if ($request->has('last_name')) {
        $user->last_name = $request->last_name;
    }
    if ($request->has('location')) {
        $user->location = $request->location;
    }
    if ($request->hasFile('profile_picture')) {
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $user->profile_picture = $path;
    }
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    if (!($user instanceof User)) {
        return response()->json(['error' => 'Invalid user object'], 500);
    }

    $user->save();

    return response()->json(['user' => $user, 'message' => 'Profile updated successfully.'], 200);


}
}
// للتجريب بس خلي الكومنت
/* public function __invoke(Request $request): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'pharmacy_name' => 'required',
            'phone_number' => 'required|unique:users,phone_number',
            'password' => 'required|min:8',
        ]);

        if($validator->fails())
        {
            return $this->sendError($validator->errors());
        }

        $input['password'] = Hash::make($input['password']);
        $input['role_id'] = 2 ;

        $user = User::create($input);
        // just to send it to the API
        $user['accessToken'] =  $user->createToken('Personal Access Token')->accessToken;
        return $this->sendResponse($user);
    }
}
*/

