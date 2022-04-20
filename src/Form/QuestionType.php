<?php

namespace App\Form;

use App\Form\CategoryType;
use App\Entity\Question;
use App\Entity\QuestionCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Заголовок вопроса',
                    'attr' => [
                        'placeholder' => 'Заголовок',
                    ],
                    'required' => true,
                ]
            )
            ->add(
                'question_text',
                TextareaType::class,
                [
                    'label' => 'Текст вопроса',
                    'attr' => [
                        'placeholder' => 'Текст',
                    ],
                    'required' => true,
                ]
            )
            ->add(
                'category',
                CategoryType::class,
                [
                    'label' => 'Категория вопроса',
                    'attr' => [
                        'placeholder' => 'Категория',
                        'list' => 'category'
                    ],
                    'required' => true,
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Добавить вопрос',
                    'attr' => [
                        'class' => 'btn btn-primary w-100'
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
