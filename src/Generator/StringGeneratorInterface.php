<?php

declare(strict_types=1);

namespace App\Generator;

interface StringGeneratorInterface
{
    public static function random(int $length): string;
}
