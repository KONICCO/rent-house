<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    //

    public function index()
    {
        # code...
        $listings = Listing::withCount('transaction')->orderBy('transaction_count', 'desc')->paginate();
        return response()->json([
            'success' => true,
            'message' => 'Get all Listing Data',
            'data' => $listings

            ]);
    }
    function show(Listing $listings)  {
        return response()->json([
            'success' => true,
            'message' => 'Get detail Listing Data ',
            'data' => $listings
        ]);
    }
}
