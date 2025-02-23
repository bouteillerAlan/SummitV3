<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/training')]
final class TrainingController extends AbstractController
{
    #[Route('/', name: 'app_training_get_all', methods: ['GET'])]
    public function getAllTraining(): JsonResponse
    {
        return $this->json([
            'message' => 'Get all trainings'
        ]);
    }

    #[Route('/{id}', name: 'app_training_get_one', methods: ['GET'])]
    public function getOneTraining(int $id): JsonResponse
    {
        return $this->json([
            'message' => 'Get training ' . $id
        ]);
    }
}
