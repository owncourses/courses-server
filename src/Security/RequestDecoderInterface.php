<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;

interface RequestDecoderInterface
{
    public function decode(Request $request): bool;
}
