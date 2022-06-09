<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use App\Services\FormService;
use App\Services\SerializeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/place')]
class PlaceController extends AbstractController
{
    private $formService;
    private $serializeService;
    private $placeRepository;

    public function __construct(
        FormService $formService,
        SerializeService $serializeService,
        PlaceRepository $placeRepository
    )
    {
        $this->formService = $formService;
        $this->serializeService = $serializeService;
        $this->placeRepository = $placeRepository;
    }

    #[Route('/new', name: 'app_place_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->placeRepository->add($place, true);
            return new Response($this->serializeService->SerializeGetLatest($this->placeRepository));
        } else {
            return new JsonResponse($this->formService->getFormErrors($form), 400, ['Content-Type', 'application/json']);
        }
    }

    #[Route('/edit/{id}', name: 'app_place_edit', methods: ['GET', 'POST', 'PUT'])]
    public function edit(Request $request, Place $place): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(PlaceType::class, $place);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->placeRepository->add($place, true);
            return new JsonResponse(['success' => true]);
        } else {
            return new JsonResponse($place, 400, ['Content-Type', 'application/json']);
        }
    }

    #[Route('/delete/{id}', name: 'app_place_delete', methods: ['POST', 'DELETE'])]
    public function delete(Place $place): Response
    {
        $this->placeRepository->remove($place, true);
        return new JsonResponse(['success' => true]);
    }
}
