<?php

namespace App\Controller;

use App\Entity\Training;
use App\Repository\TrainingRepository;
use App\Repository\UserRepository;
use App\Service\Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/training')]
final class TrainingController extends AbstractController
{
    /**
     * get all the training stored in the DB
     * @param TrainingRepository $trainingRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('', name: 'app_training_get_all', methods: ['GET'])]
    public function getAllTraining(TrainingRepository $trainingRepository, SerializerInterface $serializer): JsonResponse
    {
        $trainings = $trainingRepository->findAllPaginated('id');
        return $this->json($serializer->serialize($trainings, 'json', ['groups' => 'getAllTrainings']));
    }

    /**
     * get one training via is ID
     * @param Training $training
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'app_training_get_one', methods: ['GET'])]
    public function getOneTraining(Training $training, SerializerInterface $serializer): JsonResponse
    {
        return $this->json($serializer->serialize($training, 'json', ['groups' => 'getOneTraining']));
    }

    /**
     * create a training after validating is data
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param UserRepository $userRepository
     * @param TrainingRepository $trainingRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @return JsonResponse
     */
    #[Route('', name: 'app_training_create', methods: ['POST'])]
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
            $error = new Error('User doesn\'t exist', Response::HTTP_BAD_REQUEST);
            return new JsonResponse($serializer->serialize($error->getObject(), 'json'), Response::HTTP_BAD_REQUEST);
        }
        $newTraining->setUser($user);

        // build the response
        $jsonTraining = $serializer->serialize($newTraining, 'json', ['groups' => 'getOneTraining']);
        $location = $urlGenerator->generate('app_training_get_one', ['id' => $newTraining->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        // create the training in the db
        $trainingRepository->flushOneTraining($newTraining);

        return new JsonResponse($jsonTraining, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    /**
     * update a training via is ID
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param UserRepository $userRepository
     * @param TrainingRepository $trainingRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'app_training_update', methods: ['PUT'])]
    public function updateOneTraining(Request $request, SerializerInterface $serializer, Training $currentTraining, UserRepository $userRepository, TrainingRepository $trainingRepository): JsonResponse
    {
        // get the data and populate the current training with it
        $updatedTraining = $serializer->deserialize(
            $request->getContent(),
            Training::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentTraining]
        );

        // map the right User from the id given in request
        if ($request->toArray()['user']) {
            // fixme: later use the id stored in the auth
            $userId = $request->toArray()['user'] ?? null;
            $user = $userRepository->find($userId);
            if ($user === null) {
                $error = new Error('User doesn\'t exist', Response::HTTP_BAD_REQUEST);
                return new JsonResponse($serializer->serialize($error->getObject(), 'json'), Response::HTTP_BAD_REQUEST);
            }
            $updatedTraining->setUser($user);
        }

        // create the training in the db
        $trainingRepository->flushOneTraining($updatedTraining);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * delete a training via is ID
     * @param Training $training
     * @param TrainingRepository $trainingRepository
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'app_training_delete', methods: ['DELETE'])]
    public function deleteOneTraining(Training $training, TrainingRepository $trainingRepository): JsonResponse
    {
        $trainingRepository->deleteOneTraining($training);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
