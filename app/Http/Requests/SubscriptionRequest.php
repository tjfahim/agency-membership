<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'user_id'=>['required','exists:users,id'],
            'package_id'=>['required','exists:packages,id'],
            'payment_status' => ['required','in:Pending,Unpaid'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select a user',
            'user_id.exists' => "Selected user does not exist",
            'package_id.required' => 'Please select a package',
            'package_id.exists' => 'Selected package does not exist',
            'payment_status.required' => "Please select a payment status",
            'payment_status.in' => "Invalid payment status type",
        ];
    }
}
