<?php

namespace App\Interfaces;

interface OfferRepositoryInterface
{
    public function getAllPaginated($perPage, $onlyActive = false);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function count();
    public function getActiveOffers();
}