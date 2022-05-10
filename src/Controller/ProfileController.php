<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $countQuestions = count(
            $doctrine
                ->getRepository(Question::class)
                ->findBy(['user' => $this->getUser()])
        );

        $countAnswers = count(
            $doctrine
                ->getRepository(Answer::class)
                ->findBy(['user' => $this->getUser()])
        );

        return $this->render('profile/index.html.twig', [
            'countQuestions' => $countQuestions,
            'countAnswers' => $countAnswers,
        ]);
    }
}
