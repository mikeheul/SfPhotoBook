<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Shooting;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Shooting>
 *
 * @method Shooting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shooting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shooting[]    findAll()
 * @method Shooting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShootingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shooting::class);
    }

    public function add(Shooting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Shooting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findSearch(SearchData $search): array {

        $query = $this->createQueryBuilder('s');
        
        if(!empty($search->q) || $search->q !== null){
            $query = $query
            ->andWhere('s.title LIKE :q')
            ->setParameter('q', "%{$search->q}%");
        } 
        if(!empty($search->min) || $search->min !== null){
            $query = $query
            ->andWhere('s.price >= :min')
            ->setParameter('min', $search->min);
        } 
        if(!empty($search->max) || $search->max !== null){
            $query = $query
            ->andWhere('s.price <= :max')
            ->setParameter('max', $search->max);
        } 

        $query = $query
            ->andWhere('s.isActive = 1');

        return $query->getQuery()->getResult();
    }

//    /**
//     * @return Shooting[] Returns an array of Shooting objects
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

//    public function findOneBySomeField($value): ?Shooting
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
