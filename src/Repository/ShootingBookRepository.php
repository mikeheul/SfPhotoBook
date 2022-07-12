<?php

namespace App\Repository;

use App\Entity\Shooting;
use App\Entity\ShootingBook;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<ShootingBook>
 *
 * @method ShootingBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShootingBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShootingBook[]    findAll()
 * @method ShootingBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShootingBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShootingBook::class);
    }

    public function add(ShootingBook $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShootingBook $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBookings($id) {
        
        $qb = $this->createQueryBuilder('sb');
        
           
    }

//    /**
//     * @return ShootingBook[] Returns an array of ShootingBook objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ShootingBook
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
