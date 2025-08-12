<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CartRepositoryInterface;
use App\Models\Cart;
use App\Models\User;
use App\Exceptions\UserException;
class CartRepository implements CartRepositoryInterface
{
    public function filter(array $request, User $user)
    {
        $query = Cart::query();
        $query->select('id', 'name');
        if (isset($request['name'])) {
            $query->whereLike('name', $request['name']);
        }
        if (isset($request['per_page']) && isset($request['page_number'])) {
            return $query->paginate(perPage: $request['per_page'], page: $request['page_number']);
        }
        return $query->get();
    }

    public function find(string $id, User $user)
    {
        $cart = Cart::with('cartItems.product')->find($id);
        if (!$cart) {
            throw new UserException('Cart not found');
        }
        return $cart;
    }

    public function store(array $request, User $user)
    {
        $cart = Cart::create([
            'user_id' => $user->id,
            'name' => $request['name'],
        ]);
        return $cart;
    }

    public function update(array $request, string $id, User $user)
    {
        $cart = Cart::where('user_id', $user->id)->where('product_id', $id)->first();
        if (!$cart) {
            throw new UserException('Cart not found');
        }
        $cart->update($request);
        return $cart;
    }

    public function destroy(string $id, User $user)
    {
        $cart = Cart::where('user_id', $user->id)->where('product_id', $id)->first();
        if (!$cart) {
            throw new UserException('Cart not found');
        }
        $cart->delete();
        return $cart;
    }
}
