<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'user_id' => ['required','exists:users,id'],
            'package_id'=>['required','exists:packages,id'],
            'amount' => ['required','numeric'],
            'payment_method' => ['required','in:Cash,Bank,Card,Free'],
            'payment_status' => ['required','in:Paid,Unpaid,Due,Pending'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => "Please select a user",
            'user_id.exists' => "This user does not exist",
            'package_id.required'=>"Please select a package",
            'package_id.exists'=> "This package does not exist",
            'amount.required' => "Amount is required",
            'amount.numeric' => "Amount should be number",
            'payment_method.required' => 'Please select a payment method',
            'payment_method.in' => "Invalid payment method",
            'payment_status.required' => "Payment status is required",
            'payment_status.in' => "Invalid payment status", 
        ];
    }
}
