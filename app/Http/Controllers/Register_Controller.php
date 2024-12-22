<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Base_Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Hash;

class Register_Controller extends Base_Controller{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
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
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
        ]);

        return response()->json(['user' => $user, 'message' => 'User registered successfully.'], 201);
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

