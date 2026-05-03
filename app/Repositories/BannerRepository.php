<?php

namespace App\Repositories;

use App\Interfaces\BannerRepositoryInterface;
use App\Models\Banner;

class BannerRepository implements BannerRepositoryInterface
{
    public function getAllPaginated($perPage)
    {
        return Banner::paginate($perPage);
    }

    public function findById($id)
    {
        return Banner::find($id);
    }

    public function update($id, array $data)
    {
        $banner = Banner::find($id);
        if ($banner) {
            $banner->update($data);
            return $banner;
        }
        return null;
    }

    public function delete($id)
    {
        $banner = Banner::find($id);
        if ($banner) {
            return $banner->delete();
        }
        return false;
    }

    public function count()
    {
        return Banner::count();
    }

    public function getActiveBanners()
    {
        return Banner::where('is_active', 'active')->get();
    }
}   