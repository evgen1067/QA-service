<?php

namespace App\Controller;

use App\Entity\Question;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/', name: 'questions')]
    public function index(ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
    {
        /**
         * количество записей на странице
         */
        $questionsOnPage = 4;

        /**
         * массив записей до пагинации
         */
        $questionsBeforePaginate = $doctrine
            ->getRepository(Question::class)
            ->getQuestionOrderByDate(true);

        /**
         * текущая страница
         */
        $currentPage = $request->query->getInt('page', 1);

        /**
         * флаг предыдущей страницы
         */
        $hasPreviousPage = false;
        if ($currentPage != 1) {
            $hasPreviousPage = true;
        }

        /**
         * флаг следующей страницы
         */
        $hasNextPage = false;
        if ((count($questionsBeforePaginate) - $questionsOnPage * $currentPage) > 0) {
            $hasNextPage = true;
        }

        /**
         * пагинация записей
         */
        $questions = $paginator->paginate(
            $questionsBeforePaginate,
            $currentPage,
            $questionsOnPage
        );


        return $this->render('home/index.html.twig', [
            'questions' => $questions,
            'hasPreviousPage' => $hasPreviousPage,
            'currentPage' => $currentPage,
            'hasNextPage' => $hasNextPage,
        ]);
    }
}
