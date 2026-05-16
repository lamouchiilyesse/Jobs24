<?php

namespace App\Adapter\Doctrine\Repository;

use App\Entity\Offer;
use App\Gateway\OfferGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OfferRepository extends ServiceEntityRepository implements OfferGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function publish(Offer $offer): void
    {
        $this->_em->persist($offer);
        $this->_em->flush();

    }
}