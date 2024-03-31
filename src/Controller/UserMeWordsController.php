<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserMeWordsController extends AbstractController
{
    private $userRepo;

    public function __construct(
        private Security $security,
        UserRepository $userRepo
    ) {
        $this->userRepo = $userRepo;
    }
    public function __invoke()
    {
        $userAuth = $this->security->getUser();
        if (!$userAuth) {
            $errorMessage = (object) array('message' => 'Invalid credentials.');
            return new JsonResponse(json_encode($errorMessage), Response::HTTP_BAD_REQUEST, [], true);
        }
        $user = $this->userRepo->findOneBy(['id' => $userAuth->getId()]);
        $words = $user->getWordLearned();
        $wordsData = [];
        foreach($words as $word) {
            $wordsData[] = [
                'id' => $word->getId(),
                'value' => $word->getValue(),
                'level' => $word->getLevel()
            ];
        }
        usort($wordsData, function ($a, $b) {
            return strcmp($a['value'], $b['value']);
        });

        return $wordsData;
    }
}
