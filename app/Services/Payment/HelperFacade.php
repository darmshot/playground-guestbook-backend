<?php
declare(strict_types=1);


namespace App\Services\Payment;

use App\Services\Payment\Helpers\ReferenceExtractor;

class HelperFacade
{
    public function loanReferenceExtractor(string $text): ?string
    {
        return ReferenceExtractor::make()->from($text)->get();
    }
}
