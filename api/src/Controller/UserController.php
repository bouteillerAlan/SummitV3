<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/user')]
final class UserController extends AbstractController
{
    /**
     * get all the user stored in the DB
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('', name: 'app_user_get_all', methods: ['GET'])]
    public function getAllUser(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $usersList = $userRepository->findAllPaginated('id');
        return $this->json($serializer->serialize($usersList, 'json', ['groups' => 'getAllUsers']));
    }

    /**
     * get one user via is ID
     * @param User $user
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'app_user_get_one', methods: ['GET'])]
    public function getOneUser(User $user, SerializerInterface $serializer): JsonResponse
    {
        // reminder : EntityValueResolver automatically fetch the right ID
        //            and set the http response code
        return $this->json($serializer->serialize($user, 'json', ['groups' => 'getOneUser']));
    }

    #[Route('', name: 'app_user_create', methods: ['POST'])]
    public function createOneUser(): JsonResponse
    {
        //
    }

    #[Route('/{id}', name: 'app_user_update', methods: ['PUT'])]
    public function updateOneUser(): JsonResponse
    {
        //
    }

    /**
     * delete a user via is ID
     * @param User $user
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function deleteOneUser(User $user, UserRepository $userRepository): JsonResponse
    {
        $userRepository->deleteOneUser($user);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
