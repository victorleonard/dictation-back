<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\SentenceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserMeSentencesController extends AbstractController
{
    private $sentenceRepo;
    private $userRepo;
    private $em;

    public function __construct(
        private Security $security,
        SentenceRepository $sentenceRepo,
        UserRepository $userRepo,
        EntityManagerInterface $em
    ) {
        $this->userRepo = $userRepo;
        $this->sentenceRepo = $sentenceRepo;
        $this->em = $em;
    }
    public function __invoke()
    {
        $userAuth = $this->security->getUser();
        if (!$userAuth) {
            $errorMessage = (object) array('message' => 'Invalid credentials.');
            return new JsonResponse(json_encode($errorMessage), Response::HTTP_BAD_REQUEST, [], true);
        }
        $sentences = $userAuth->getSentenceLearned();
        $sentencesData = [];
        foreach($sentences as $s) {
            $sentencesData[] = [
                'id' => $s->getId(),
                'value' => $s->getValue(),
                'level' => $s->getLevel()
            ];
        }
        return $sentencesData;
        /* $sentence = $this->sentenceRepo->findOneBy(['id' => $id]);
        $sentence->addUser($userAuth);
        $this->em->persist($sentence);
        $this->em->flush();
        return 'ok'; */
    }
}
