<?php

namespace App\Http\Controllers;

use App\Http\Resources\MedicineResource;
use App\Models\FavoriteMedicine;
use App\Models\Favorites;
use App\Models\Medicine;
use App\Models\products;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavoritesController extends Base_Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // $user = auth()->user();
        $lang = request('lang');
        $Products = products::query()
            ->when(
                $lang == 'ar',
                function ($query) {
                    return $query
                        ->select(
                            'id',
                            'category_id',
                            'company_id',
                            'scientific_name_AR as scientific_name',
                            'economic_name_AR as economic_name',
                            'image',
                            'unit_price'
                        )
                        ->with([
                            'category:id,name_AR as name',
                            'company:id,name_AR as name',
                            'batches:medicine_id,amount,expiration_date'
                        ]);
                },
                function ($query) {
                    return $query
                        ->select(
                            'id',
                            'category_id',
                            'company_id',
                            'scientific_name_EN as scientific_name',
                            'economic_name_EN as economic_name',
                            'image',
                            'unit_price'
                        )
                        ->with([
                            'category:id,name_EN as name',
                            'company:id,name_EN as name',
                            'batches:medicine_id,amount,expiration_date'
                        ])
                       ;
                }
            )
          //->whereHas('favorite_users' , function($query) use($user){
            //    return $query->where('user_id' , $user->id);
            //})
            ->get();

        foreach($Products as $Product){
            if($Product['image'] != null){
                $medicine['image'] = base64_encode(file_get_contents(public_path($Products['image'])));
            }
        }
        return $this->sendResponse($Products , 'favorites');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all() , [
           'product_id' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
        // first i should make sure that this product doesn't belong to the favorite list of that user
        // if it was there, then I should delete it
        $user = Auth::user();
        $was_favorite = Favorites::query()
            ->where([
                ['user_id' , '=' , $user->id],
                ['product_id' , '=' , $request['product_id']]
            ])->first();

        if(!is_null($was_favorite)){
            return $this->destroy($was_favorite);
        }
        $favorite = Favorites::create([
            'user_id' => $user->id,
            'product_id' => $request['product_id']
        ]);
        return $this->sendResponse($favorite, 'adding_favorite');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorites $favoriteProduct): JsonResponse
    {
        $favoriteProduct->delete();
        return $this->sendResponse([] , 'destroy_favorite');
    }
}
