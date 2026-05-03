<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfferRequest;
use App\Services\OfferService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class OfferController extends Controller
{
    private OfferService $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);

            // If user is admin, show all, otherwise only active
            $user = $request->user('sanctum');
            $isAdmin = $user && ($user->role === 'admin' || $user->role === 'super_admin');

            $offers = $this->offerService->getAllOffers($perPage, !$isAdmin);

            return response()->json([
                'status' => true,
                'message' => 'Offers fetched successfully',
                'data' => $offers->items(),
                'pagination' => [
                    'current_page' => $offers->currentPage(),
                    'last_page' => $offers->lastPage(),
                    'per_page' => $offers->perPage(),
                    'total' => $offers->total(),
                ]
            ], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch offers'], 500);
        }
    }

    public function store(OfferRequest $request)
    {
        try {
            $offer = $this->offerService->createOffer($request->all());

            return response()->json(['status' => true, 'message' => 'Offer created successfully', 'data' => $offer], 201);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to create offer', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $offer = $this->offerService->getOfferById($id);

            if (!$offer) {
                return response()->json(['status' => false, 'message' => 'Offer not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Offer fetched successfully', 'data' => $offer], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch offer'], 500);
        }
    }

    public function update(OfferRequest $request, $id)
    {
        try {
            $offer = $this->offerService->updateOffer($id, $request->all());

            if (!$offer) {
                return response()->json(['status' => false, 'message' => 'Offer not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Offer updated successfully', 'data' => $offer], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to update offer', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->offerService->deleteOffer($id);

            if (!$deleted) {
                return response()->json(['status' => false, 'message' => 'Offer not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Offer deleted successfully'], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to delete offer'], 500);
        }
    }

    public function getTotalOffers()
    {
        $total = $this->offerService->getTotalOffersCount();
        return response()->json(['status' => true, 'total_offers' => $total]);
    }
}
