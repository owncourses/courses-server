<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;

final class RequestDecoder implements RequestDecoderInterface
{
    public const REQUEST_TOKEN_KEY = 'X-AUTH-TOKEN';

    private $securitySecret;

    public function __construct(string $securitySecret)
    {
        $this->securitySecret = $securitySecret;
    }

    public function decode(Request $request): bool
    {
        $token = hash_hmac('sha1', $request->getContent(), $this->securitySecret);
        if ($request->headers->get(self::REQUEST_TOKEN_KEY) !== 'sha1='.$token) {
            return false;
        }

        return true;
    }
}
