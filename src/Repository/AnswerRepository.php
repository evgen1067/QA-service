<?php

namespace App\Repository;

use App\Entity\Answer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    /**
     * @param Answer $entity
     * @param bool $flush
     * @return void
     */
    public function add(Answer $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Answer $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Answer $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param int $id id вопроса
     * @param bool $moderationStatus статус модерации вопроса
     * @return array
     */
    public function getAnswersOnQuestion(int $id, bool $moderationStatus): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery('
            SELECT a.id AS id,
                   a.answer_date AS date,
                   a.answer_correctness AS correctness,
                   a.answer_text AS text,
                   u.name AS author
            FROM App\Entity\Answer a,
                 App\Entity\User u
            WHERE a.question = :id AND
                  a.user = u.id AND
                  a.moderation_status = :moderation_status
            ORDER BY a.answer_date')
            ->setParameter('id', $id)
            ->setParameter('moderation_status', $moderationStatus);

        return $query->getResult();
    }
}
