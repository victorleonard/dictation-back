<?php

namespace App\Controller;

use App\Repository\WordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserMeWordController extends AbstractController
{
    private $wordRepo;
    private $em;

    public function __construct(
        private Security $security,
        WordRepository $wordRepo,
        EntityManagerInterface $em
    ) {
        $this->wordRepo = $wordRepo;
        $this->em = $em;
    }
    public function __invoke($id)
    {
        $userAuth = $this->security->getUser();
        if (!$userAuth) {
            $errorMessage = (object) array('message' => 'Invalid credentials.');
            return new JsonResponse(json_encode($errorMessage), Response::HTTP_BAD_REQUEST, [], true);
        }
        $word = $this->wordRepo->findOneBy(['id' => $id]);
        $word->addUser($userAuth);
        $this->em->persist($word);
        $this->em->flush();
        return 'ok';
    }
}
