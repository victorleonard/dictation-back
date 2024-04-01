<?php

namespace App\Controller;

use App\Repository\WordErrorRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WordsErrorUserController extends AbstractController
{
    private $wordErrorRepo;

    public function __construct(
        private Security $security,
        WordErrorRepository $wordErrorRepo
    ) {
        $this->wordErrorRepo = $wordErrorRepo;
    }
    public function __invoke($id)
    {
        $errors = $this->wordErrorRepo->findErrorByUser($id);
        return $errors;
    }
}
