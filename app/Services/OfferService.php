<?php

namespace App\Services;

use App\Interfaces\OfferRepositoryInterface;
use Exception;

class OfferService
{
    private OfferRepositoryInterface $offerRepository;

    public function __construct(OfferRepositoryInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function getAllOffers($perPage = 10, $onlyActive = false)
    {
        return $this->offerRepository->getAllPaginated($perPage, $onlyActive);
    }

    public function createOffer(array $data)
    {
        return $this->offerRepository->create($data);
    }

    public function getOfferById($id)
    {
        return $this->offerRepository->findById($id);
    }

    public function updateOffer($id, array $data)
    {
        return $this->offerRepository->update($id, $data);
    }

    public function deleteOffer($id)
    {
        return $this->offerRepository->delete($id);
    }

    public function getTotalOffersCount()
    {
        return $this->offerRepository->count();
    }
}
