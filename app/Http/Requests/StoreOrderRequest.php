<?php

namespace App\Http\Requests;

use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === User::ROLE_CUSTOMER;
    }

    public function rules(): array
    {
        return [
            'restaurant_id' => ['required', 'exists:'.Restaurant::class.',id'],
            'delivery_address' => ['required', 'string', 'max:255'],
            'menu_item_id' => ['required', 'exists:'.MenuItem::class.',id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ];
    }
}
