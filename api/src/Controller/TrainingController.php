<?php

namespace App\Controller;

use App\Entity\Training;
use App\Repository\TrainingRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/training')]
final class TrainingController extends AbstractController
{
    #[Route('', name: 'app_training_get_all', methods: ['GET'])]
    public function getAllTraining(TrainingRepository $trainingRepository, SerializerInterface $serializer): JsonResponse
    {
        $trainings = $trainingRepository->findAllPaginated('id');
        return $this->json($serializer->serialize($trainings, 'json', ['groups' => 'getAllTrainings']));
    }

    #[Route('', name: 'app_training_create_one', methods: ['POST'])]
    public function createOneTraining(
        Request $request, SerializerInterface $serializer,
        UserRepository $userRepository, ValidatorInterface $validator
    ): JsonResponse
    {
        // get the data from the request
        $newTraining = $serializer->deserialize($request->getContent(), Training::class, 'json');

        // validate the data
        $errors = $validator->validate($newTraining);
        if ($errors->count() > 0) return new JsonResponse(
            $serializer->serialize($errors, 'json'),
            Response::HTTP_BAD_REQUEST,
            [],
            true
        );

        // map the right User from the id given in request
        $userId = $request->toArray()['user'] ?? null;
        $newTraining->setUser($userRepository->find($userId));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'app_training_get_one', methods: ['GET'])]
    public function getOneTraining(Training $training, SerializerInterface $serializer): JsonResponse
    {
        return $this->json($serializer->serialize($training, 'json', ['groups' => 'getOneTraining']));
    }

    #[Route('/{id}', name: 'app_training_delete_one', methods: ['DELETE'])]
    public function deleteOneTraining(Training $training, TrainingRepository $trainingRepository): JsonResponse
    {
        $trainingRepository->deleteOneTraining($training);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
