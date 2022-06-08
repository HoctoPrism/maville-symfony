<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModifyPasswordType;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Services\FormService;
use App\Services\SerializeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user')]
class UserController extends AbstractController
{
    private $passwordHasher;
    private $formService;
    private $userRepository;
    private $serializeService;
    private $mailer;

    public function __construct(
        UserPasswordHasherInterface  $passwordHasher, 
        FormService $formService,
        UserRepository $userRepository, 
        SerializeService $serializeService,
        MailerInterface $mailer
    )
    {
        $this->passwordHasher = $passwordHasher;
        $this->formService = $formService;
        $this->userRepository = $userRepository;
        $this->serializeService = $serializeService;
        $this->mailer = $mailer;
    }


    // Get the actual user
    #[Route('/current-user', name: 'user', methods: ['GET', 'HEAD'])]
    public function index(): Response
    {
        return $this->json($this->getUser());
    }
    
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $genPassword = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#$%^&'), 15, 15);
        $data["password"] = $this->passwordHasher->hashPassword($user, $genPassword);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            $html = $this->renderView('user/emailBody.html.twig', [
                'person' => $data,
                'password' => $genPassword,
            ]);

            $email = (new Email())
            ->from($_ENV['MailAdmin'])
            ->to($_ENV['MailAdmin'])
            ->subject('Inscription à la plateforme Ma ville')
            ->html($html);

            $mailer->send($email);

            return new Response($this->serializeService->SerializeGetLatest($this->userRepository));
        } else {
            return new JsonResponse($this->formService->getFormErrors($form), 400, ['Content-Type', 'application/json']);
        }
    }

    #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST', 'PUT'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(UserType::class, $user);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return new JsonResponse(['success' => true]);
        } else {
            return new JsonResponse($user, 400, ['Content-Type', 'application/json']);
        }
    }

    #[Route('/delete/{id}', name: 'app_user_delete', methods: ['POST', 'DELETE'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        $userRepository->remove($user, true);
        
        return new JsonResponse(['success' => true]);
    }

    // Reset du mot de passe
    #[Route('/modifyPassword/{id}', name: 'user_password_modify', methods: ['PUT'])]
    public function passwordReset(Request $request, User $user, UserPasswordHasherInterface $passwordEncoder,  UserRepository $userRepository)
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(ModifyPasswordType::class, $user);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('newPassword')->getData() === $form->get('confirmPassword')->getData()) {
                $password = $passwordEncoder->hashPassword($user, $form->get('newPassword')->getData());
                $user->setPassword($password);
                $userRepository->add($user, true);

                $html = $this->renderView('user/emailConfirmPasswordChange.html.twig', [
                    'person' => $user,
                ]);
    
                $email = (new Email())
                    ->from($_ENV['MailAdmin'])
                    ->to($_ENV['MailAdmin'])
                    ->subject('Changement de mot de passe sur la plateforme Replay')
                    ->html($html);
    
    
                $this->mailer->send($email);

                return new JsonResponse(['success' => true]);

            } else {
                return new JsonResponse(['newPassword' => 'les mots de passe doivent correspondre'], 400,
                    ['Content-Type', 'application/json']);
            }
        } else {
            if ($form->get('newPassword')->getData() != $form->get('confirmPassword')->getData()) {
                return new JsonResponse(
                    [
                        'oldPassword' => "Mot de passe actuel invalide",
                        'newPassword' => 'les mots de passe doivent correspondre'
                    ], 400, ['Content-Type', 'application/json']);
            } else {
                return new JsonResponse($this->formService->getFormErrors($form), 400, ['Content-Type', 'application/json']);
            }
        }
    }
}
