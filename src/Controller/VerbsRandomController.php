<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\VerbRepository;
use App\Repository\WordRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VerbsRandomController extends AbstractController
{
    private $verbRepo;

    public function __construct(
        private Security $security,
        VerbRepository $verbRepo
    ) {
        $this->verbRepo = $verbRepo;
    }
    public function __invoke()
    {
        $userAuth = $this->security->getUser();
        if (!$userAuth) {
            $errorMessage = (object) array('message' => 'Invalid credentials.');
            return new JsonResponse(json_encode($errorMessage), Response::HTTP_BAD_REQUEST, [], true);
        }
        $word = $this->verbRepo->findRandomWord();
        return $word;
    }
}
