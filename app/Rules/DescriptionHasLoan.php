<?php

namespace App\Rules;

use App\Facades\PaymentHelper;
use App\Models\Loan;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DescriptionHasLoan implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute is not valid.');

            return;
        }

        $loanReference = PaymentHelper::loanReferenceExtractor($value);

        if (empty($loanReference)) {
            $fail('The :attribute is contain loan reference.');

            return;
        }

        if (Loan::query()->where('reference', $loanReference)->exists() === false) {
            $fail('The loan reference is contained in :attribute not found.');
        }
    }
}
