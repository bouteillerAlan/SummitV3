<?php

namespace App\Controller;

use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/training')]
final class TrainingController extends AbstractController
{
    #[Route('/', name: 'app_training_get_all', methods: ['GET'])]
    public function getAllTraining(TrainingRepository $trainingRepository): JsonResponse
    {
        $trainings = $trainingRepository->findAll();
        return $this->json($trainings);
    }

    #[Route('/{id}', name: 'app_training_get_one', methods: ['GET'])]
    public function getOneTraining(int $id, TrainingRepository $trainingRepository): JsonResponse
    {
        $training = $trainingRepository->findOneBy(['id' => $id]);
        return $this->json($training);
    }
}
