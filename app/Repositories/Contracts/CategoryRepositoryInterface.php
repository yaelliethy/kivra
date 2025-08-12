<?php

namespace App\Repositories\Contracts;

interface CategoryRepositoryInterface
{
    public function all();
    public function filter(array $request);
    public function find(String $id);
    public function delete(String $id);
    public function create(array $data);
    public function update(String $id, array $data);
}
