<?php

namespace App\Repository;

use App\Entity\RadiogrammeError;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RadiogrammeError>
 *
 * @method RadiogrammeError|null find($id, $lockMode = null, $lockVersion = null)
 * @method RadiogrammeError|null findOneBy(array $criteria, array $orderBy = null)
 * @method RadiogrammeError[]    findAll()
 * @method RadiogrammeError[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RadiogrammeErrorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RadiogrammeError::class);
    }

    /**
     * Trouve les erreurs non résolues
     *
     * @return RadiogrammeError[]
     */
    public function findUnresolved(): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.isResolved = :resolved')
            ->setParameter('resolved', false)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Marque toutes les erreurs résolues comme traitées
     */
    public function markResolvedAsProcessed(): int
    {
        return $this->createQueryBuilder('r')
            ->update()
            ->set('r.isResolved', ':resolved')
            ->where('r.updatedAt IS NOT NULL')
            ->setParameter('resolved', true)
            ->getQuery()
            ->execute();
    }

    /**
     * Trouve les erreurs par type
     *
     * @return RadiogrammeError[]
     */
    public function findByErrorType(string $errorType): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.errorType = :errorType')
            ->setParameter('errorType', $errorType)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
