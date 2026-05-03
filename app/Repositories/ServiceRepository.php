<?php

namespace App\Repositories;

use App\Interfaces\ServiceRepositoryInterface;
use App\Models\Service;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function getAll()
    {
        return Service::all();
    }

    public function findById($id)
    {
        return Service::find($id);
    }

    public function create(array $data)
    {
        return Service::create($data);
    }

    public function update($id, array $data)
    {
        $service = Service::find($id);
        if ($service) {
            $service->update($data);
            return $service;
        }
        return null;
    }

    public function delete($id)
    {
        $service = Service::find($id);
        if ($service) {
            return $service->delete();
        }
        return false;
    }
}
