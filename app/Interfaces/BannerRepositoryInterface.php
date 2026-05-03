<?php
namespace App\Interfaces;

interface BannerRepositoryInterface
{

    public function findById($id);
    public function update($id, array $data);
    public function delete($id);
    public function count();
    public function getActiveBanners();
}   