<?php

namespace App\Adapter\Doctrine\Repository;

use App\Entity\JobSeeker;
use App\Gateway\JobSeekerGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 *
 * @method JobSeeker|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobSeeker|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobSeeker[]    findAll()
 * @method JobSeeker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobSeekerRepository extends ServiceEntityRepository implements JobSeekerGateway
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobSeeker::class);
    }

    public function register(JobSeeker $jobSeeker): void {
        $em = $this->getEntityManager();
        $em->persist($jobSeeker);
        $em->flush();

    }

//    /**
//     * @return JobSeeker[] Returns an array of JobSeeker objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?JobSeeker
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
