<?php

namespace App\Controller;

    use App\Services\FormService;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{
    private $formService;

    public function __construct(FormService $formService){
        $this->formService = $formService;
    }

    // Route for the api
    #[Route('/api', name: 'api')]
    public function api()
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }

}
