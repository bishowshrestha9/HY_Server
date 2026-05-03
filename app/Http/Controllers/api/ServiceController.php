<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Services\ServiceService;
use Illuminate\Http\Request;
use Exception;

class ServiceController extends Controller
{
    private ServiceService $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $services = $this->serviceService->getAllServices($perPage);

            if ($services->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No services found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Services fetched successfully',
                'data' => $services->items(),
                'pagination' => [
                    'current_page' => $services->currentPage(),
                    'last_page' => $services->lastPage(),
                    'per_page' => $services->perPage(),
                    'total' => $services->total(),
                ]
            ], 200);

        } catch (Exception $e) {
            \Log::error('Failed to fetch services', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to fetch services'], 500);
        }
    }

    public function store(ServiceRequest $request)
    {
        try {
            $this->serviceService->createService($request->validated(), $request->file('images'));

            return response()->json(['status' => true, 'message' => 'Service created successfully'], 201);

        } catch (Exception $e) {
            \Log::error('Failed to create service', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to create service'], 500);
        }
    }

    public function show($slug)
    {
        try {
            $service = $this->serviceService->getServiceBySlug($slug);

            if (!$service) {
                return response()->json(['status' => false, 'message' => 'Service not found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Service fetched successfully',
                'data' => $service,
            ], 200);

        } catch (Exception $e) {
            \Log::error('Failed to fetch service', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to fetch service'], 500);
        }
    }

    public function update(ServiceRequest $request, $id)
    {
        try {
            $service = $this->serviceService->updateService($id, $request->validated(), $request->file('images'));

            if (!$service) {
                return response()->json(['status' => false, 'message' => 'Service not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Service updated successfully'], 200);

        } catch (Exception $e) {
            \Log::error('Failed to update service', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to update service'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->serviceService->deleteService($id);

            if (!$deleted) {
                return response()->json(['status' => false, 'message' => 'Service not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Service deleted successfully'], 200);

        } catch (Exception $e) {
            \Log::error('Failed to delete service', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to delete service'], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $service = $this->serviceService->toggleStatus($id);

            if (!$service) {
                return response()->json(['status' => false, 'message' => 'Service not found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Service status updated successfully',
                'new_status' => $service->is_active,
            ], 200);

        } catch (Exception $e) {
            \Log::error('Failed to toggle service status', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to update service status'], 500);
        }
    }

    public function getServiceIdAndName()
    {
        try {
            $data = $this->serviceService->getServiceIdAndName();
            return response()->json(['status' => true, 'data' => $data]);

        } catch (Exception $e) {
            \Log::error('Failed to get service IDs', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to get service IDs and names'], 500);
        }
    }

    public function getTopSixServices()
    {
        try {
            $services = $this->serviceService->getTopSixServices();
            return response()->json([
                'status' => true,
                'message' => 'Top six services fetched successfully',
                'data' => $services,
            ], 200);

        } catch (Exception $e) {
            \Log::error('Failed to fetch top services', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to fetch top six services'], 500);
        }
    }

    public function getTotalServices()
    {
        try {
            $total = $this->serviceService->getTotalServicesCount();
            return response()->json(['status' => true, 'total_services' => $total]);
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to get total services'], 500);
        }
    }
}
