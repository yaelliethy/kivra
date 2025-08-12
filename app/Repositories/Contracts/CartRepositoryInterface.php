<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface CartRepositoryInterface
{
    public function filter(array $request, User $user);
    public function find(String $id, User $user);
    public function store(array $request, User $user);
    public function update(array $request, String $id, User $user);
    public function destroy(String $id, User $user);
}
