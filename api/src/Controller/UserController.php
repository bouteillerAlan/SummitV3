<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_get_all', methods: ['GET'])]
    public function getAllUser(UserRepository $userRepository): JsonResponse
    {
        $usersList = $userRepository->findAll();
        return $this->json($usersList);
    }

    #[Route('/{id}', name: 'app_user_get_one', methods: ['GET'])]
    public function getOneUser(int $id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->findOneBy(['id' => $id]);
        return $this->json($user);
    }
}
