<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\PaymentRepositoryInterface;
use App\Repositories\PaymentRepository;
use App\Interfaces\BookingRepositoryInterface;
use App\Repositories\BookingRepository;
use App\Interfaces\BlogRepositoryInterface;
use App\Repositories\BlogRepository;
use App\Interfaces\ServiceRepositoryInterface;
use App\Repositories\ServiceRepository;
use App\Interfaces\InstructorRepositoryInterface;
use App\Repositories\InstructorRepository;
use App\Interfaces\InquiryRepositoryInterface;
use App\Repositories\InquiryRepository;
use App\Interfaces\ReviewRepositoryInterface;
use App\Repositories\ReviewRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Interfaces\OfferRepositoryInterface;
use App\Repositories\OfferRepository;
use App\Interfaces\BannerRepositoryInterface;
use App\Repositories\BannerRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(InstructorRepositoryInterface::class, InstructorRepository::class);
        $this->app->bind(InquiryRepositoryInterface::class, InquiryRepository::class);
        $this->app->bind(ReviewRepositoryInterface::class, ReviewRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);
        $this->app->bind(BannerRepositoryInterface::class, BannerRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
