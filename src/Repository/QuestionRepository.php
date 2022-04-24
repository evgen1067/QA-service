<?php

namespace App\Repository;

use App\Entity\Question;
use App\Entity\QuestionCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * @param Question $entity
     * @param bool $flush
     * @return void
     */
    public function add(Question $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Question $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Question $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param bool $moderationStatus статус модерации
     * @return array массив с информацией о вопросах
     */
    public function getQuestionOrderByDate(bool $moderationStatus): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery('
            SELECT q.id AS id,
                   q.title AS title,
                   q.question_text AS text,
                   q.question_date AS date,
                   qc.category_name AS category,
                   (
                       SELECT count(a.id)
                       FROM App\Entity\Answer a
                       WHERE q.id = a.question AND
                             a.moderation_status = true
                   ) AS answerCount
            FROM App\Entity\Question q,
                 App\Entity\QuestionCategory qc
                 WHERE q.category = qc.id AND
                  q.moderation_status = :moderation_status
            ORDER BY q.question_date DESC')
            ->setParameter('moderation_status', $moderationStatus);

        return $query->getResult();
    }

    /**
     * @param int $id id вопроса
     * @return array массив с информацией о вопросе
     */
    public function getQuestionDetailPage(int $id): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery('
            SELECT q.id AS id,
                   q.title AS title,
                   q.question_text AS text,
                   q.question_date AS date,
                   qc.category_name AS category,
                   (
                       SELECT count(a.id)
                       FROM App\Entity\Answer a
                       WHERE q.id = a.question AND
                             a.moderation_status = true
                   ) AS answerCount,
                   u.name AS author
            FROM App\Entity\Question q,
                 App\Entity\QuestionCategory qc,
                 App\Entity\User u
                 WHERE q.category = qc.id AND
                       q.id = :id AND
                       q.user = u.id')
            ->setParameter('id', $id);

        return $query->getResult();
    }
}
