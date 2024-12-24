<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Base_Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Base_Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Revoke the token that was used to authenticate the request
            $request->user()->token()->revoke();

            return response()->json([
                'status' => 'success',
                'message' => 'User logged out successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to log out. Please try again.',
            ], 500);
        }
    }
}



/*<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Base_Controller;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Base_Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();
        return response()->json([
           'status' => 'success'
        ]);
    }
}
*/
