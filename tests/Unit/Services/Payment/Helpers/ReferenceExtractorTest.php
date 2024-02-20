<?php
declare(strict_types=1);


namespace Tests\Unit\Services\Payment\Helpers;

use App\Services\Payment\Helpers\ReferenceExtractor;
use PHPUnit\Framework\TestCase;

class ReferenceExtractorTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_result(): void
    {
        $reference = 'LN20221212';

        $description = "Some desc with
        $reference

        .";


        $this->assertEquals($reference, ReferenceExtractor::make()
            ->from($description)->get());

        $description = "Some desc with


        .";

        $this->assertNull(ReferenceExtractor::make()
            ->from($description)->get());
    }
}
