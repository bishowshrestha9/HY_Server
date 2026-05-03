<?php

namespace App\Interfaces;

interface InquiryRepositoryInterface
{
    public function getAllPaginated($perPage);
    public function findById($id);
    public function create(array $data);
    public function delete($id);
    public function count();
}
