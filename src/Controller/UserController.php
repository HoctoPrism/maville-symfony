<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Services\FormService;
use App\Services\SerializeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user')]
class UserController extends AbstractController
{
    private $passwordHasher;
    private $formService;
    private $userRepository;
    private $serializeService;

    public function __construct(
        UserPasswordHasherInterface  $passwordHasher, 
        FormService $formService,
        UserRepository $userRepository, 
        SerializeService $serializeService
    )
    {
        $this->passwordHasher = $passwordHasher;
        $this->formService = $formService;
        $this->userRepository = $userRepository;
        $this->serializeService = $serializeService;
    }


    // Get the actual user
    #[Route('/api/current-user', name: 'user', methods: ['GET', 'HEAD'])]
    public function index(): Response
    {
        return $this->json($this->getUser());
    }
    
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $data["password"] = $this->passwordHasher->hashPassword($user, $data['password']);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);
            return new Response($this->serializeService->SerializeGetLatest($this->userRepository));
        } else {
            return new JsonResponse($this->formService->getFormErrors($form), 400, ['Content-Type', 'application/json']);
        }
    }

    #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(UserType::class, $user);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return new JsonResponse(['success' => true]);
        } else {
            return new JsonResponse($this->formService->getFormErrors($form), 400, ['Content-Type', 'application/json']);
        }
    }

    #[Route('/delete/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        $userRepository->remove($user, true);
        
        return new JsonResponse(['success' => true]);
    }
}
