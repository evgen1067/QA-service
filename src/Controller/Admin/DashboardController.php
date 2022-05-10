<?php

namespace App\Controller\Admin;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\QuestionCategory;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @param AdminUrlGenerator $adminUrlGenerator
     */
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(QuestionCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('QAservices.');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Назад', 'fas fa-home', '/');

        yield MenuItem::subMenu('Пользователи', 'fas fa-bars')
            ->setSubItems(
                [
                    MenuItem::linkToCrud(
                        'Пользователи',
                        'fas fa-eye',
                        User::class
                    )
                ]
            );

        yield MenuItem::subMenu('Вопросы', 'fas fa-bars')
            ->setSubItems(
                [
                    MenuItem::linkToCrud(
                        'Новый вопрос',
                        'fas fa-plus',
                        Question::class
                    )->setAction(Crud::PAGE_NEW),
                    MenuItem::linkToCrud(
                        'Вопросы',
                        'fas fa-eye',
                        Question::class
                    )
                ]
            );

        yield MenuItem::subMenu('Ответы', 'fas fa-bars')
            ->setSubItems(
                [
                    MenuItem::linkToCrud(
                        'Новый ответ',
                        'fas fa-plus',
                        Answer::class
                    )->setAction(Crud::PAGE_NEW),
                    MenuItem::linkToCrud(
                        'Ответы',
                        'fas fa-eye',
                        Answer::class
                    )
                ]
            );

        yield MenuItem::subMenu('Категории', 'fas fa-bars')
            ->setSubItems(
                [
                    MenuItem::linkToCrud(
                        'Новая категория',
                        'fas fa-plus',
                        QuestionCategory::class
                    )->setAction(Crud::PAGE_NEW),
                    MenuItem::linkToCrud(
                        'Категории',
                        'fas fa-eye',
                        QuestionCategory::class
                    )
                ]
            );
    }
}
