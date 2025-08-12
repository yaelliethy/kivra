<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface CartItemRepositoryInterface
{
    public function filter(array $data, User $user);
    public function details(String $id, User $user);
    public function addToCart(array $data, User $user);
    public function removeFromCart(String $id, User $user);

    public function updateQuantity(String $id, User $user, int $quantity);
}
