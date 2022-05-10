<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\QuestionCategory;
use App\Entity\User;
use App\Form\QuestionType;
use App\Form\CategoryType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddQuestionController extends AbstractController
{
    #[Route('/add/question', name: 'app_add_question')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        # создание формы
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        # получение уже существующих категорий для подсказок
        $categories = $doctrine
            ->getRepository(QuestionCategory::class)
            ->findAll();

        # обработка формы
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # получаю текущего пользователя
            $user = $this->getUser();

            # получаю данные формы
            $question = $form->getData();
            $question
                ->setQuestionDate(new \DateTime('now'))
                ->setModerationStatus(false)
                ->setUser($user);

            # получаю категорию
            $category = $question->getCategory();

            if (! $category->getCategoryName()) {
                $category->setCategoryName('Без категории');
            }

            # проверка, что такой категории еще нет
            $categoryCheck = $doctrine
                ->getRepository(QuestionCategory::class)
                ->findOneBy(['category_name' => $category->getCategoryName()]);

            $em = $doctrine->getManager();

            # если такой нет, то добавляю, иначе устанавливаю уже имеющеюся
            if (! $categoryCheck) {
                $em->persist($category);
                $question->setCategory($category);
            } else {
                $question->setCategory($categoryCheck);
            }
            $em->persist($question);

            # отправляю полученные данные в БД
            $em->flush();

            return $this->redirectToRoute('questions');
        }

        return $this->renderForm('add_question/index.html.twig', [
            'form' => $form,
            'categories' => $categories
        ]);
    }
}
