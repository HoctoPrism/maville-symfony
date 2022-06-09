<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use App\Services\FormService;
use App\Services\SerializeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tag')]
class TagController extends AbstractController
{
    private $formService;
    private $serializeService;
    private $tagRepository;

    public function __construct(
        FormService $formService,
        SerializeService $serializeService,
        TagRepository $tagRepository
    )
    {
        $this->formService = $formService;
        $this->serializeService = $serializeService;
        $this->tagRepository = $tagRepository;
    }

    #[Route('/new', name: 'app_tag_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $data['cancelled'] = false;
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagRepository->add($tag, true);
            return new Response($this->serializeService->SerializeGetLatest($this->tagRepository));
        } else {
            return new JsonResponse($this->formService->getFormErrors($form), 400, ['Content-Type', 'application/json']);
        }
    }

    #[Route('/edit/{id}', name: 'app_tag_edit', methods: ['GET', 'POST', 'PUT'])]
    public function edit(Request $request, Tag $tag): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(TagType::class, $tag);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagRepository->add($tag, true);
            return new JsonResponse(['success' => true]);
        } else {
            return new JsonResponse($tag, 400, ['Content-Type', 'application/json']);
        }
    }

    #[Route('/delete/{id}', name: 'app_tag_delete', methods: ['POST', 'DELETE'])]
    public function delete(Tag $tag): Response
    {
        $this->tagRepository->remove($tag, true);
        return new JsonResponse(['success' => true]);
    }
}
