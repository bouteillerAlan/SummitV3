<?php

namespace App\Controller;

use App\Entity\Training;
use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/training')]
final class TrainingController extends AbstractController
{
    #[Route('/', name: 'app_training_get_all', methods: ['GET'])]
    public function getAllTraining(TrainingRepository $trainingRepository, SerializerInterface $serializer): JsonResponse
    {
        $trainings = $trainingRepository->findAllPaginated('id');
        return $this->json($serializer->serialize($trainings, 'json', ['groups' => 'getAllTrainings']));
    }

    #[Route('/{id}', name: 'app_training_get_one', methods: ['GET'])]
    public function getOneTraining(Training $training, SerializerInterface $serializer): JsonResponse
    {
        return $this->json($serializer->serialize($training, 'json', ['groups' => 'getOneTraining']));
    }
}
