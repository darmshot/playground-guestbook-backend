<?php
declare(strict_types=1);


namespace App\Services\Payment\Helpers;

class ReferenceExtractor
{
    private string $text;

    public static function make(): static
    {
        return new self();
    }

    public function from(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function get(): ?string
    {
        preg_match('/(LN[0-9]{8})/i', $this->text, $matches);

        return empty($matches[0]) ? null : strtoupper($matches[0]);
    }
}
