<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

final class SignedRequestAuthenticator extends AbstractGuardAuthenticator
{
    private $requestDecoder;

    public function __construct(RequestDecoderInterface $requestDecoder)
    {
        $this->requestDecoder = $requestDecoder;
    }

    public function supports(Request $request)
    {
        return $request->headers->has(RequestDecoder::REQUEST_TOKEN_KEY);
    }

    public function getCredentials(Request $request): Request
    {
        return $request;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return new User();
    }

    public function checkCredentials($request, UserInterface $user)
    {
        return $this->requestDecoder->decode($request);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required',
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
