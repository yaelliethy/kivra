<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Exceptions\UserException;
use App\Models\Cart;
use App\Models\OrderItem;

class OrderRepository implements OrderRepositoryInterface
{
    public function filter(array $request, User $user): Collection
    {
        $query = Order::query();
        $query->where('user_id', $user->id);
        if (isset($request['per_page']) && isset($request['page_number'])) {
            return $query->paginate(perPage: $request['per_page'], page: $request['page_number']);
        }
        return $query->get();
    }

    public function create(array $data): Order
    {
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $data['user_id'],
                'cart_id' => $data['cart_id'],
                'total_price' => 0,
            ]);
            $cart = Cart::where('id', $data['cart_id'])->where('user_id', $data['user_id'])->first();
            if (!$cart) {
                throw new UserException('Cart not found');
            }
            $items = $cart->items;
            $totalPrice = 0;
            foreach ($items as $item) {
                $totalPrice += $item->price * $item->quantity;
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }
            $order->total_price = $totalPrice;
            $order->save();
            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}