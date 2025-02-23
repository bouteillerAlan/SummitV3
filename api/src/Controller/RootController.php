<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class RootController extends AbstractController
{
    #[Route('/', name: 'app_root', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json(['message' => 'I\'m alive!']);
    }
}
