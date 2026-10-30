<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Exception;

class DashboardController extends Controller
{
    private DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;

    }

    public function getDashboardData()
    {
        try {
            $data = $this->dashboardService->getDashboardStats();

            return response()->json([
                'status' => true,
                'data' => $data
            ], 200);

        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Failed to fetch dashboard data',
            ], 500);
        }
    }
}
