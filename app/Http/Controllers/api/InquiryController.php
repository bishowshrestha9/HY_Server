<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryRequest;
use App\Services\InquiryService;
use Illuminate\Http\Request;
use Exception;

class InquiryController extends Controller
{
    private InquiryService $inquiryService;

    public function __construct(InquiryService $inquiryService)
    {
        $this->inquiryService = $inquiryService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $inquiries = $this->inquiryService->getAllInquiries($perPage);

            if ($inquiries->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No inquiries found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Inquiries fetched successfully',
                'data' => $inquiries->items(),
                'pagination' => [
                    'current_page' => $inquiries->currentPage(),
                    'last_page' => $inquiries->lastPage(),
                    'per_page' => $inquiries->perPage(),
                    'total' => $inquiries->total(),
                ]
            ], 200);

        } catch (Exception $e) {
            \Log::error('Failed to fetch inquiries', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to fetch inquiries'], 500);
        }
    }

    public function store(InquiryRequest $request)
    {
        try {
            $this->inquiryService->createInquiry($request->validated(), $request->ip(), $request->userAgent());

            return response()->json([
                'status' => true,
                'message' => 'Inquiry submitted successfully',
            ], 201);

        } catch (Exception $e) {
            // Check for specific error codes thrown by service
            $code = $e->getCode();
            $statusCode = ($code >= 400 && $code < 500) ? $code : 500;

            \Log::error('Failed to create inquiry', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage() ?: 'Failed to submit inquiry',
            ], $statusCode);
        }
    }

    public function show($id)
    {
        try {
            $inquiry = $this->inquiryService->getInquiryById($id);

            if (!$inquiry) {
                return response()->json(['status' => false, 'message' => 'Inquiry not found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Inquiry fetched successfully',
                'data' => $inquiry,
            ], 200);

        } catch (Exception $e) {
            \Log::error('Failed to fetch inquiry', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to fetch inquiry'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->inquiryService->deleteInquiry($id);

            if (!$deleted) {
                return response()->json(['status' => false, 'message' => 'Inquiry not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Inquiry deleted successfully'], 200);

        } catch (Exception $e) {
            \Log::error('Failed to delete inquiry', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to delete inquiry'], 500);
        }
    }

    public function getTotalInquiry()
    {
        $total = $this->inquiryService->getTotalInquiriesCount();
        return response()->json(['status' => true, 'total_inquiries' => $total]);
    }
}
