<?php

namespace App\Services;

use App\Interfaces\ServiceRepositoryInterface;
use Illuminate\Support\Str;
use App\Models\Service; // Needed for specific query in getTopSixServices if generic repo is insufficient, or extend interface

class ServiceService
{
    private ServiceRepositoryInterface $serviceRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function getAllServices($perPage = 10)
    {
        // Repository currently returns all, but controller used pagination
        // Using model directly for pagination since repository interface defined 'getAll' as simply all()
        // Ideally we update interface, but for now let's use the model in service to keep repo simple or fix repo.
        // I will fix repo method usage here or assume repo changes.
        // Actually, let's just use Model in service if repo method is missing for pagination or update repo?
        // Updating repo is better. But I already wrote it.
        // I'll use Model::with... here for now or standard pagination.
        // The controller uses `Service::with('instructor')->paginate($perPage)`.

        $services = Service::with('instructor')->paginate($perPage);

        $services->getCollection()->transform(function ($service) {
            $this->transformService($service);
            return $service;
        });

        return $services;
    }

    public function createService(array $data, $images = null)
    {
        $data['slug'] = strtolower(str_replace(' ', '-', $data['title']));

        if (isset($data['benefits']) && is_string($data['benefits'])) {
            $data['benefits'] = array_map('trim', explode(',', $data['benefits']));
        }

        if (isset($data['class_schedule']) && is_string($data['class_schedule'])) {
            $data['class_schedule'] = array_map('trim', explode(',', $data['class_schedule']));
        }

        // Process pricing_options if provided
        if (isset($data['pricing_options']) && is_array($data['pricing_options'])) {
            $data['pricing_options'] = $this->normalizePricingOptions($data['pricing_options']);
        }

        if ($images) {
            $imagePaths = [];
            $this->ensurePublicDirectoryExists('services');

            foreach ($images as $image) {
                $extension = $image->getClientOriginalExtension();
                $filename = 'service_' . time() . '_' . Str::random(10) . '.' . $extension;
                $image->move(public_path('services'), $filename);
                $imagePaths[] = 'services/' . $filename;
            }
            $data['images'] = $imagePaths;
        }

        return $this->serviceRepository->create($data);
    }

    public function getServiceBySlug($slug)
    {
        $service = Service::with('instructor')->where('slug', $slug)->first();
        if ($service) {
            $this->transformService($service);
        }
        return $service;
    }

    public function updateService($id, array $data, $images = null)
    {
        $service = $this->serviceRepository->findById($id);
        if (!$service)
            return null;

        if (isset($data['benefits']) && is_string($data['benefits'])) {
            $data['benefits'] = array_map('trim', explode(',', $data['benefits']));
        }

        if (isset($data['class_schedule']) && is_string($data['class_schedule'])) {
            $data['class_schedule'] = array_map('trim', explode(',', $data['class_schedule']));
        }

        // Process pricing_options if provided
        if (isset($data['pricing_options']) && is_array($data['pricing_options'])) {
            $data['pricing_options'] = $this->normalizePricingOptions($data['pricing_options']);
        }

        if ($images) {
            // Delete old images
            if ($service->images) {
                foreach ($service->images as $oldImage) {
                    $this->deletePublicFile($oldImage);
                }
            }

            $imagePaths = [];
            $this->ensurePublicDirectoryExists('services');

            foreach ($images as $image) {
                $extension = $image->getClientOriginalExtension();
                $filename = 'service_' . time() . '_' . Str::random(10) . '.' . $extension;
                $image->move(public_path('services'), $filename);
                $imagePaths[] = 'services/' . $filename;
            }
            $data['images'] = $imagePaths;
        }

        return $this->serviceRepository->update($id, $data);
    }

    public function deleteService($id)
    {
        $service = $this->serviceRepository->findById($id);
        if (!$service)
            return false;

        if ($service->images) {
            foreach ($service->images as $image) {
                $this->deletePublicFile($image);
            }
        }

        return $this->serviceRepository->delete($id);
    }

    public function toggleStatus($id)
    {
        $service = $this->serviceRepository->findById($id);
        if (!$service)
            return null;

        $service->is_active = !$service->is_active;
        $service->save();
        return $service;
    }

    public function getTopSixServices()
    {
        $services = Service::where('is_active', true)
            ->with('instructor')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $services->transform(function ($service) {
            $this->transformService($service);
            return $service;
        });

        return $services;
    }

    public function getTotalServicesCount()
    {
        return Service::count();
    }

    public function getServiceIdAndName()
    {
        return Service::select('id', 'title')->get();
    }

    private function transformService($service)
    {
        $service->image_urls = $service->images ? array_map(function ($image) {
            return $this->publicAssetUrl($image);
        }, $service->images) : [];

        // Transform pricing_options to include full URLs for images
        if ($service->pricing_options && is_array($service->pricing_options)) {
            $service->pricing_options = array_map(function ($option) {
                if (isset($option['images']) && is_array($option['images'])) {
                    $option['image_urls'] = array_map(function ($image) {
                        // Check if it's already a full URL or a local path
                        if (filter_var($image, FILTER_VALIDATE_URL)) {
                            return $image;
                        }
                        return $this->publicAssetUrl($image);
                    }, $option['images']);
                }
                return $option;
            }, $service->pricing_options);
        }

        $service->instructor_name = $service->instructor ? $service->instructor->name : null;
        unset($service->instructor);
        unset($service->instructor_id);
        unset($service->images);
        unset($service->class_schedule);
    }

    /**
     * Normalize and validate pricing options data structure
     * 
     * @param array $pricingOptions
     * @return array
     */
    private function normalizePricingOptions(array $pricingOptions): array
    {
        return array_map(function ($option) {
            return [
                'label' => $option['label'] ?? null,
                'price' => (float)($option['price'] ?? 0),
                'discount_price' => isset($option['discount_price']) ? (float)$option['discount_price'] : null,
                'currency' => $option['currency'] ?? 'NPR',
                'min_pax' => (int)($option['min_pax'] ?? 1),
                'max_pax' => (int)($option['max_pax'] ?? 1),
                'description' => $option['description'] ?? null,
                'images' => isset($option['images']) && is_array($option['images']) ? $option['images'] : [],
            ];
        }, $pricingOptions);
    }

    private function publicAssetUrl(string $path): string
    {
        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, strlen('storage/'));
        }

        $publicPath = public_path($cleanPath);

        if (!file_exists($publicPath)) {
            $storagePath = storage_path('app/public/' . $cleanPath);

            if (file_exists($storagePath)) {
                $this->ensurePublicDirectoryExists(dirname($cleanPath));
                @copy($storagePath, $publicPath);
            }
        }

        return asset($cleanPath);
    }

    private function deletePublicFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, strlen('storage/'));
        }

        $publicPath = public_path($cleanPath);
        $storagePath = storage_path('app/public/' . $cleanPath);

        if (file_exists($publicPath)) {
            @unlink($publicPath);
        }

        if (file_exists($storagePath)) {
            @unlink($storagePath);
        }
    }

    private function ensurePublicDirectoryExists(string $directory): void
    {
        if ($directory === '.' || $directory === '') {
            return;
        }

        $path = public_path($directory);

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }
}
