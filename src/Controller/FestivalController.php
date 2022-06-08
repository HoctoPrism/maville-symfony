<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Form\FestivalType;
use App\Repository\FestivalRepository;
use App\Services\FormService;
use App\Services\SerializeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/festival')]
class FestivalController extends AbstractController
{
    private $formService;
    private $festivalRepository;
    private $serializeService;

    public function __construct(
        FormService $formService,
        FestivalRepository $festivalRepository,
        SerializeService $serializeService
    )
    {
        $this->formService = $formService;
        $this->festivalRepository = $festivalRepository;
        $this->serializeService = $serializeService;
    }
    #[Route('/new', name: 'app_festival_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FestivalRepository $festivalRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $festival = new Festival();
        $form = $this->createForm(FestivalType::class, $festival);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $festivalRepository->add($festival, true);
            return new Response($this->serializeService->SerializeGetLatest($this->festivalRepository));
        } else {
            return new JsonResponse($this->formService->getFormErrors($form), 400, ['Content-Type', 'application/json']);
        }
    }

    #[Route('/edit/{id}', name: 'app_festival_edit', methods: ['GET', 'POST', 'PUT'])]
    public function edit(Request $request, Festival $festival, FestivalRepository $festivalRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(FestivalType::class, $festival);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $festivalRepository->add($festival, true);

            return new JsonResponse(['success' => true]);
        } else {
            return new JsonResponse($festival, 400, ['Content-Type', 'application/json']);
        }
    }

    #[Route('/delete/{id}', name: 'app_festival_delete', methods: ['POST'])]
    public function delete(Request $request, Festival $festival, FestivalRepository $festivalRepository): Response
    {
        $festivalRepository->remove($festival, true);        
        return new JsonResponse(['success' => true]);
    }
}
