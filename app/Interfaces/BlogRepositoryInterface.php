<?php

namespace App\Interfaces;

interface BlogRepositoryInterface
{
    public function getAllPaginated($perPage);
    public function findById($id);
    public function findBySlug($slug);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function count();
}
