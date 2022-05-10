<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\User;
use App\Entity\Question;
use App\Form\AnswerType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionPageController extends AbstractController
{
    #[Route('/question/{id}', name: 'question')]
    public function index(int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $question = $doctrine->getRepository(Question::class)->findOneBy(['id' => $id]);

        if ($question->getModerationStatus()) {
            # получаю текущего пользователя
            /**
             * @var User $user
             */
            $user = $this->getUser();

            $questionDetailPage = $doctrine->getRepository(Question::class)->getQuestionDetailPage($id);

            $answers = $doctrine->getRepository(Answer::class)->getAnswersOnQuestion($id, true);

            if ($user) {
                # создание формы
                $answer = new Answer();

                $answer
                    ->setQuestion($question)
                    ->setAnswerDate(new \DateTime('now'))
                    ->setAnswerCorrectness(0)
                    ->setModerationStatus(false)
                    ->setUser($user);

                $form = $this->createForm(AnswerType::class, $answer);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    # получаю данные формы
                    $answer = $form->getData();

                    $em = $doctrine->getManager();

                    $em->persist($answer);

                    $em->flush();

                    return $this->redirectToRoute('question', ['id' => $question->getId()]);
                }

                return $this->renderForm('question_page/index.html.twig', [
                    'question' => $questionDetailPage,
                    'answers' => $answers,
                    'form' => $form,
                ]);
            }

            return $this->render('question_page/index.html.twig', [
                'question' => $questionDetailPage,
                'answers' => $answers,
            ]);
        }

        return $this->redirectToRoute('questions');
    }

    #[Route('/question_help/{id}/{qId}', name: 'answer')]
    public function helpedQuestion(int $id, int $qId, ManagerRegistry $doctrine): Response
    {
        $answer = $doctrine
            ->getRepository(Answer::class)
            ->findOneBy(['id' => $id]);

        $answer->setAnswerCorrectness(
            $answer->getAnswerCorrectness() + 1
        );

        $em = $doctrine->getManager();

        $em->persist($answer);

        $em->flush();

        return $this->redirectToRoute('question', ['id' => $qId]);
    }
}
