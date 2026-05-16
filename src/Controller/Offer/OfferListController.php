<?php

namespace App\Controller\Offer;

use App\Adapter\Doctrine\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class OfferListController extends AbstractController
{
// src/Controller/OfferListController.php

    public function __invoke(OfferRepository $repository): Response
    {
        return $this->render('UI/Offer/index.html.twig', [
            // Fetch only non-deleted offers
            'offers' => $repository->findBy(['deletedAt' => null], ['publishedAt' => 'DESC']),
        ]);
    }
}