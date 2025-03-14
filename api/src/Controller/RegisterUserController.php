<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RegisterUserController extends AbstractController
{
    #[Route('/api/user/create', name: 'app_register_user', methods: ['POST'])]
    public function index(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
    ): JsonResponse
    {
        $requestPayload = $request->getPayload()->all();
        $user = new User();
        $user->setEmail($requestPayload['email'] ?? '');
        $user->setPassword($passwordHasher->hashPassword($user, $requestPayload['password'] ?? ''));
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json(array_map(function ($value) {
                return $value->getMessage();
            }, $errors->getIterator()->getArrayCopy()), 400);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User created successfully',
        ]);
    }
}
