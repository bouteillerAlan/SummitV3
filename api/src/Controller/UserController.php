<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_get_all', methods: ['GET'])]
    public function getAllUser(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $usersList = $userRepository->findAllPaginated('id');
        return $this->json($serializer->serialize($usersList, 'json', ['groups' => 'getAllUsers']));
    }

    #[Route('/{id}', name: 'app_user_get_one', methods: ['GET'])]
    public function getOneUser(User $user, SerializerInterface $serializer): JsonResponse
    {
        // reminder : EntityValueResolver automatically fetch the right ID
        //            and set the http response code
        return $this->json($serializer->serialize($user, 'json', ['groups' => 'getOneUser']));
    }

    #[Route('/{id}', name: 'app_user_delete_one', methods: ['DELETE'])]
    public function deleteOneUser(User $user, UserRepository $userRepository): JsonResponse
    {
        $userRepository->deleteOneUser($user);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
