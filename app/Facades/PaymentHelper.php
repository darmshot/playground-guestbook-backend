<?php

declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string|null loanReferenceExtractor(string $text)
 *
 */
class PaymentHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'PaymentHelper';
    }
}
