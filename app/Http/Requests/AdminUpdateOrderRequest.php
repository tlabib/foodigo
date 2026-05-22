<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === User::ROLE_ADMIN;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([
                    Order::STATUS_PENDING,
                    Order::STATUS_CONFIRMED,
                    Order::STATUS_PREPARING,
                    Order::STATUS_OUT_FOR_DELIVERY,
                    Order::STATUS_DELIVERED,
                    Order::STATUS_CANCELLED,
                ]),
            ],
            'rider_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
