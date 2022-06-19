<?php

namespace App\Repository;

use App\Entity\WalletOperation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WalletOperation>
 *
 * @method WalletOperation|null find($id, $lockMode = null, $lockVersion = null)
 * @method WalletOperation|null findOneBy(array $criteria, array $orderBy = null)
 * @method WalletOperation[]    findAll()
 * @method WalletOperation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletOperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WalletOperation::class);
    }

    public function add(WalletOperation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WalletOperation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
