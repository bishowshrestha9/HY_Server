<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function getAllPaginated($perPage = 10);
    public function findById($id);
    public function findByEmail($email);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
