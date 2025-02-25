<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Transaction\Store;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionController extends Controller
{
    //
    public function index()
    {
        $transactions = Transaction::with('listing')
    ->whereUserId(Auth::id())
    ->paginate();

        return response()->json($transactions);
    }
    private function _fullyBookedChecker(Store $request)
    {
        $listing = \App\Models\Listing::find($request->listing_id);

        $runningTransactionCount = Transaction::whereListingId($listing->id)
            ->whereNot('status', 'canceled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [
                    $request->start_date,
                    $request->end_date,
                ])->orWhereBetween('end_date', [
                    $request->start_date,
                    $request->end_date,
                ])->orWhere(function ($subquery) use ($request) {
                    $subquery->where('start_date', '<', $request->start_date)
                        ->where('end_date', '>', $request->end_date);
                });
            })->count();
        if ($runningTransactionCount >= $listing->max_person) {
            return throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Listing is fully booked',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }
        return true;
    }
    public function isAvailaible(Store $request)
    {
        $this->_fullyBookedChecker($request);
        return response()->json([
            'success' => true,
            'message' => 'Listing is available',
        ]);
    }

    public function store(Store $request)
    {
        # code...
        $this->_fullyBookedChecker($request);
        $transaction = Transaction::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'listing_id' => $request->listing_id,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created',
            'data' => $transaction->listing
        ]);
    }
    public function show(Transaction $transaction)
    {
        # code...
        if  ($transaction->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        return response()->json([
            'success' => true,
            'message' => 'Transaction detail',
            'data' => $transaction->listing()
        ]);
    }
}
