<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Cart;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->user() === null){
            return false;
        }
        $cart = Cart::where('id', $this->cart_id)->first();
        if (!$cart) {
            return false;
        }
        return $cart->user()->id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cart_id' => 'required|string|exists:carts,id',
            'address' => 'required|string|max:255',
        ];
    }
}
