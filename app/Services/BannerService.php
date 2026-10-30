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
        if (isset($data['is_active'])) {
            $isActive = $data['is_active'];
            if ($isActive === 'active' || $isActive === true || $isActive === 1 || $isActive === '1') {
                $data['is_active'] = 'active';
            } else {
                $data['is_active'] = 'inactive';
            }
        }

        $banner = Banner::find($id);
        if (!$banner) {
            $banner = new Banner();
            $banner->id = $id;
            $banner->banner_text = $data['banner_text'] ?? 'Default Banner Text';
            $banner->banner_link = $data['banner_link'] ?? '#';
            $banner->is_active = $data['is_active'] ?? 'active';
            $banner->background_color = $data['background_color'] ?? '#000000';
            $banner->text_color = $data['text_color'] ?? '#ffffff';
            $banner->save();
            return $banner;
        }

        $banner->update($data);
        return $banner;
    }



}