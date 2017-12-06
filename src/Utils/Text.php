<?php declare(strict_types=1);

namespace App\Utils;

class Text
{
    public function snakeCase(string $text): string
    {
        $createdString = '';
        for ($i = 0; $i < strlen($text); $i++) {

            if ($i > 0 && $text{$i} === strtoupper($text{$i})) {
                $createdString .= '_';
            }

            $createdString .= strtolower($text{$i});
        }
        return $createdString;
    }
}
