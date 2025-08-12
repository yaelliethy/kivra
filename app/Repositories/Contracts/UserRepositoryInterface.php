<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function filter(array $filters);
    public function getById(string $id);
    public function create(array $data);
    public function update(array $data, User $user);
    public function updatePassword(array $data, string $id);
    public function delete(string $id);
}
