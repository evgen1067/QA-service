<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\QuestionCategory;
use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $roles = [
            'ROLE_USER',
            'ROLE_ADMIN',
        ];
        // пользователь
        $user = new User();
        $password = $this->hasher->hashPassword($user, '123456');
        $user->setName('Егор Сергеев')
            ->setEmail('sergeev.egor48@mail.ru')
            ->setPassword($password)
            ->setApiToken(Uuid::v1()->toRfc4122())
            ->setRoles($roles);
        $manager->persist($user);

        $user = new User();
        $password = $this->hasher->hashPassword($user, '123456');
        $user->setName('Евгений Богомолов')
            ->setEmail('bogomolov.evgen1067@mail.ru')
            ->setPassword($password)
            ->setApiToken(Uuid::v1()->toRfc4122())
            ->setRoles($roles);
        $manager->persist($user);

        // категория вопроса (Карьера в IT)
        $questionCategory1 = new QuestionCategory();
        $questionCategory1->setCategoryName('Карьера в IT');
        $manager->persist($questionCategory1);

        for ($i = 1; $i < 51; $i++) {
            // вопрос Карьера в IT
            $question = new Question();
            $question->setUser($user)
                ->setCategory($questionCategory1)
                ->setTitle('Заголовок №' . $i)
                ->setQuestionText('Текст вопроса №' . $i)
                ->setQuestionDate(new \DateTime('now + ' . $i . ' hours'))
                ->setModerationStatus(true);
            $manager->persist($question);

            // ответ Карьера в IT
            $answer = new Answer();
            $answer->setUser($user)
                ->setQuestion($question)
                ->setAnswerText('Тестовый ответ на вопрос №' . $i)
                ->setAnswerDate(new \DateTime('now + ' . $i . ' hours'))
                ->setAnswerCorrectness(0)
                ->setModerationStatus(true);
            $manager->persist($answer);
        }

        for ($i = 51; $i < 101; $i++) {
            // вопрос Карьера в IT
            $question1 = new Question();
            $question1->setUser($user)
                ->setCategory($questionCategory1)
                ->setTitle('Заголовок №' . $i)
                ->setQuestionText('Текст вопроса №' . $i)
                ->setQuestionDate(new \DateTime('now + ' . $i . ' hours'));
            $manager->persist($question1);

            // ответ Карьера в IT
            $answer1 = new Answer();
            $answer1->setUser($user)
                ->setQuestion($question)
                ->setAnswerText('Тестовый ответ на вопрос №' . $i)
                ->setAnswerDate(new \DateTime('now + ' . $i . ' hours'))
                ->setAnswerCorrectness(0);
            $manager->persist($answer1);
        }

        $manager->flush();
    }
}
