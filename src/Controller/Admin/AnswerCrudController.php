<?php

namespace App\Controller\Admin;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnswerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Answer::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextareaField::new('answer_text', 'Текст ответа'),
            DateTimeField::new('answer_date', 'Дата добавления ответа')->hideOnForm(),
            IntegerField::new('answer_correctness', 'Верность ответа')->hideOnForm(),
            BooleanField::new('moderation_status', 'Статус модерации'),
            AssociationField::new('question', 'Вопрос'),
            AssociationField::new('user', 'Пользователь'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Answer) {
            return;
        }

        $entityInstance->setAnswerDate(new \DateTime('now'));

        $entityInstance->setAnswerCorrectness(0);

        parent::persistEntity($entityManager, $entityInstance);
    }
}
