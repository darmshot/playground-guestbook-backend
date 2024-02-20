<?php

namespace App\Http\Requests;

use App\Data\Payment\PaymentForm;
use App\Rules\DescriptionHasLoan;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'firstname' => 'string|min:2|max:64',
            'lastname' => 'string|min:2|max:64',
            'payment_date' => 'date',
            'amount' => 'decimal:2|required|min:0.01|max:100000',
            'description' => ['string','min:10','max:255',new DescriptionHasLoan],
            'ref_id' => 'uuid|max:36|unique:App\Models\Payment,ref_id'
        ];
    }

    public function payload(): PaymentForm
    {
        $data = (object)$this->validated();

        return new PaymentForm(
            firstname: $data->firstname,
            lastname: $data->lastname,
            payment_date: $data->payment_date,
            amount: $data->amount,
            description: $data->description,
            ref_id: $data->ref_id ?? null,
        );
    }
}
