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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

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
    public function createOneTraining(Request $request, SerializerInterface $serializer, UserRepository $userRepository, TrainingRepository $trainingRepository, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        // get the data from the request
        $newTraining = $serializer->deserialize($request->getContent(), Training::class, 'json');

        // validate the data
        $dataIsValid = $trainingRepository->validateTraining($newTraining);
        if ($dataIsValid !== null) {
            return new JsonResponse($serializer->serialize($dataIsValid, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }

        // map the right User from the id given in request
        // fixme: later use the id stored in the auth
        $userId = $request->toArray()['user'] ?? null;
        $user = $userRepository->find($userId);
        if ($user === null) {
            return new JsonResponse($serializer->serialize('User doesn\'t exist', 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }
        $newTraining->setUser($user);

        // build the response
        $jsonTraining = $serializer->serialize($newTraining, 'json', ['groups' => 'getOneTraining']);
        $location = $urlGenerator->generate('app_training_get_one', ['id' => $newTraining->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        // create the training in the db
        $trainingRepository->createOneTraining($newTraining);

        return new JsonResponse($jsonTraining, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    // todo: 25/02
    // todo: edit a training
    // todo: create and edit a user
    // todo: check l'auth w/ jwt
    // todo: add caching

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
