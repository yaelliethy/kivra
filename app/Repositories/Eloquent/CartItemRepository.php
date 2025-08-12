<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CartItemRepositoryInterface;
use App\Models\CartItem;
use App\Models\User;
use App\Exceptions\UserException;
use App\Models\Cart;
class CartItemRepository implements CartItemRepositoryInterface
{
    public function filter(array $data, User $user)
    {
        $cart = Cart::where('id', $data['cart_id'])->where('user_id', $user->id)->first();
        if (!$cart) {
            throw new UserException('Cart not found');
        }
        $query = CartItem::query();
        $query->with('product');
        $query->select('id', 'quantity', 'product_id');
        if (isset($data['product_name'])) {
            $query->whereHas('product', function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['product_name'] . '%');
            });
        }
        if (isset($data['per_page']) && isset($data['page_number'])) {
            return $query->paginate(perPage: $data['per_page'], page: $data['page_number']);
        }
        return $query->get();
    }
    public function details(string $id, User $user)
    {
        $cartItem = CartItem::where('id', $id)->whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->first();
        if (!$cartItem) {
            throw new UserException('Cart item not found');
        }
        return $cartItem;
    }

    public function addToCart(array $data, User $user)
    {
        $cartItem = CartItem::create([
            'cart_id' => $data['cart_id'],
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'user_id' => $user->id,
        ]);
        return $cartItem;

    }

    public function removeFromCart(string $id, User $user)
    {
        $cartItem = CartItem::where('id', $id)->where('user_id', $user->id)->delete();
        return $cartItem;
    }

    public function updateQuantity(string $id, User $user, int $quantity)
    {
        $cartItem = CartItem::where('id', $id)->where('user_id', $user->id)->first();
        if (!$cartItem) {
            throw new UserException('Cart item not found');
        }
        $cartItem->quantity = $quantity;
        $cartItem->save();
        return $cartItem;
    }
}
