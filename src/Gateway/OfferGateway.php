<?php

namespace App\Gateway;

use App\Entity\Offer;

interface OfferGateway
{
    public function publish(Offer $offer): void;
}