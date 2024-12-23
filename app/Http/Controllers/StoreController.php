<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
    // app/Http/Controllers/StoreController.php

use App\Models\Store;
use App\Models\Product;
use App\Models\products;
use App\Models\Stores;

class StoreController extends Controller
{
    // Show all stores
    public function index()
    {
        $stores = Stores::with('products')->get();
        return response()->json($stores);
    }

    // Show a specific store and its products
    public function show($id)
    {
        $store = Stores::with('products')->findOrFail($id);
        return response()->json($store);
    }

    // Show products in a specific store
   // Show products in a specific store
   public function products($storeId)
   {
       $query = products::query();
       $products = $query->where('store_id', $storeId)->get();
       return response()->json($products);
   }


}


