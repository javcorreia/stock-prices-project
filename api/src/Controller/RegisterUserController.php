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
use OpenApi\Attributes as OA;

final class RegisterUserController extends AbstractController
{
    #[Route('/api/user/create', name: 'app_register_user', methods: ['POST'])]
    #[OA\Post(
        description: 'Creates a new user in the system.',
        summary: 'Create a new user',
    )]
    #[OA\RequestBody(
        description: 'JSON payload to user registration',
        required: true,
        content: new OA\JsonContent(
            properties: [
                'email' => new OA\Property(
                    property: 'email',
                    description: 'The user email',
                    type: 'string',
                    format: 'email',
                ),
                'password' => new OA\Property(
                    property: 'password',
                    description: 'Define a password',
                    type: 'string',
                    format: 'password',
                )
            ],
            type: 'object',
        )
    )]
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
        $user->setPassword(
            !empty($requestPayload['password'])
                ? $passwordHasher->hashPassword($user, $requestPayload['password'])
                : ''
        );
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
