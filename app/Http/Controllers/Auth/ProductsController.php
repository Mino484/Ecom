<?php

namespace App\Http\Controllers;

use App\Http\Resources\MedicineResource;
use App\Models\{Category, Stores,  Favorites, products, Role,};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
 use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\{DB, Validator};

class ProductsController extends Base_Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(): JsonResponse
    {
        $lang = request('lang');
        $products = $this->get_product($lang);

        return $this->sendResponse($products, "products");
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /* TODO: checking if the given medicine is already in the stock
         there are two important things, the first one is:
         when the admin wants to add a new medicine that doesn't exist in the stock
         then he should write all the information about it
         but when the product is already in the stock,

        */

        $validator = Validator::make($request->all(), [
            "category_id" => 'required',
            "Store_name_EN" => 'required',
            "Store_name_AR" => 'required',
            "name_EN" => 'required',
            "name_AR" => 'required',
            "amount" => 'required',
            "expiration_date" => 'required|date',
            "unit_price" => 'required',
            'image' => ['image' , 'mimes:jpeg,png,bmp,jpg,gif,svg']
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $image= $request->file('image');

        $product_image = null;
        if($request->hasFile('image')){
            $product_image = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('image'),$product_image);
            $product_image='image/'.$product_image ;
        }


        $Store = DB::table('Stores')
            ->where('name_EN', 'regexp', $request['name_EN'])
            ->first();
        if (is_null($Store)) {
            $Store = Stores::create([
                'name_EN' => $request['name_EN'],
                'name_AR' => $request['name_AR']
            ]);
        }
        $product = products::create($request->all());
        $Product = products::create([
            "category_id" => $request['category_id'],
            "Store_id" => $Store->id,
            "name_EN" => $request['name_EN'],
            "name_AR" => $request['name_AR'],
            "unit_price" => $request["unit_price"],
            "image" => $product_image
        ]);

    }


    /**
     * Display the specified resource.
     */

    public function show($id): JsonResponse
    {
        // this function return the information to the user
        // TODO: i should make a function to return all the Products to the admin

        $lang = request('lang');
       $Product = $this->get_product($lang, $id);

        return $this->sendResponse($Product, "products");

        }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $name)
    {
        // doing s simple validation to the category_id and company_id
        if ($request['category_id'] != null && !Category::where('id', $request['category_id'])->exists()) {
            return $this->sendError("the category id isn't valid");
        }
        if ($request['Store_id'] != null && !Stores::where('id', $request['Store_id'])->exists()) {
            return $this->sendError("the Store id isn't valid");
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(products $product)
    {
        //
    }

    /**
     * @param mixed $lang
     */
    public function get_product(mixed $lang, $id = null)
{
    // Logic remains unchanged
    $page = request('page');
    $products = products::query() // Corrected to use Product model
        ->when(
            $lang == 'ar',
            function ($query) use ($id) {
                $query->select(
                    'id',
                    'category_id',
                    'Store_id',
                    'name_AR as name',
                    'image',
                    'unit_price'
                )->with([
                    'category:id,name_AR as name',
                    'Store:id,name_AR as name'
                ]);
                if ($id === null) {
                    $query->with('batches:product_id,amount');
                }
                return $query->filter(request(['category', 'search']));
            },
            function ($query) use ($id) {
                $query->select(
                    'id',
                    'category_id',
                    'Store_id',
                    'name_EN as name',
                    'image',
                    'unit_price'
                )->with([
                    'category:id,name_EN as name',
                    'Store:id,name_EN as name'
                ]);
                if ($id === null) {
                    $query->with('batches:product_id,amount');
                }
                return $query->filter(request(['category', 'search']));
            }
        )
        ->withCount('favorite_users as popularity')
        ->orderBy('popularity', 'DESC')
        ->when(
            $id === null,
            function ($query) use ($page) {
                if ($page === null) {
                    return $query->get(); // Return all products if not paginated
                }
                return $query->paginate(10)->withQueryString();
            },
            function ($query) use ($id) {
                return $query->find($id);
            }
        );

    return $products; // Ensure the method returns the product data
}

}
