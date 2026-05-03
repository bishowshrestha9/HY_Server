<?php

namespace App\Services;
use App\Models\Banner;

class BannerService
{
    public function getAllBanners()
    {
        return Banner::all();
    }

    public function updateBanner($id, array $data)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return null;
        }
        $banner->update($data);
        return $banner;
    }



}