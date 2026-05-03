<?php
namespace App\Repositories;

use App\Interfaces\OfferRepositoryInterface;
use App\Models\Offer;

class OfferRepository implements OfferRepositoryInterface
{
    public function getAllPaginated($perPage, $onlyActive = false)
    {
        $query = Offer::with('service');
        if ($onlyActive) {
            $query->where('is_active', true);
        }
        return $query->paginate($perPage);
    }

    public function findById($id)
    {
        return Offer::with('service')->find($id);
    }

    public function create(array $data)
    {
        return Offer::create($data);
    }

    public function update($id, array $data)
    {
        $offer = Offer::find($id);
        if ($offer) {
            $offer->update($data);
            return $offer;
        }
        return null;
    }

    public function delete($id)
    {
        $offer = Offer::find($id);
        if ($offer) {
            return $offer->delete();
        }
        return false;
    }

    public function count()
    {
        return Offer::count();
    }

    public function getActiveOffers()
    {
        // Fixing the boolean check and including service relationship
        return Offer::with('service')->where('is_active', true)->get();
    }
}