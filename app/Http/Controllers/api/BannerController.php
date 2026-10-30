<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Services\BannerService;
use Illuminate\Http\Request;

class BannerController extends Controller{

    private BannerService $bannerService;
    //This is comment for testing purpose.
    

       public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;

    }

    public function index()
    {
        return $this->bannerService->getAllBanners();
    }

   

    public function update(Request $request, $id)
    {
        return $this->bannerService->updateBanner($id, $request->all());
    }




}