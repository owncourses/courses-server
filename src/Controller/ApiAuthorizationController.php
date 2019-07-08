<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ApiAuthorizationController extends AbstractController
{
    private $integrationApiKey;

    public function __construct(string $integrationApiKey)
    {
        $this->integrationApiKey = $integrationApiKey;
    }

    public function authorization(Request $request): Response
    {
        if ($this->integrationApiKey !== $request->request->get('api_key', $request->headers->get('X-API-KEY'))) {
            return new JsonResponse(['success' => false], Response::HTTP_FORBIDDEN);
        }

        return new JsonResponse(['success' => true]);
    }
}
