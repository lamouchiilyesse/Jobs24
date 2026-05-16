<?php

namespace App\Adapter\Doctrine\Repository;

use App\Entity\Recruiter;
use App\Gateway\RecruiterGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 *
 * @method Recruiter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recruiter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recruiter[]    findAll()
 * @method Recruiter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecruiterRepository extends ServiceEntityRepository implements RecruiterGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recruiter::class);
    }

    public function register(Recruiter $recruiter): void {
        $em = $this->getEntityManager();
        $em->persist($recruiter);
        $em->flush();

    }

//    /**
//     * @return Recruiter[] Returns an array of Recruiter objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recruiter
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
