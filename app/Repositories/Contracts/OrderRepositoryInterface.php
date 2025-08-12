<?php

namespace App\Repositories\Contracts;
use App\Models\Order;
use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    public function filter(array $request, User $user): Collection;
    public function create(array $data): Order;
}