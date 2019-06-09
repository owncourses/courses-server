<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ApiAuthorizationController extends AbstractController
{
    public function authorization(Request $request): Response
    {
        return new JsonResponse(['api_key' => $request->request->get('api_key')]);
    }
}
