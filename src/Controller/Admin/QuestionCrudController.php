<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class QuestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Question::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Заголовок вопроса'),
            TextareaField::new('question_text', 'Текст вопроса'),
            DateTimeField::new('question_date', 'Дата создания вопроса')->hideOnForm(),
            BooleanField::new('moderation_status', 'Статус модерации'),
            AssociationField::new('category', 'Категория вопроса')
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Question) {
            return;
        }

        $entityInstance->setQuestionDate(new \DateTime('now'));

        parent::persistEntity($entityManager, $entityInstance);
    }
}
