<?php

namespace App\Adapter\InMemory\Repository;

use App\Entity\Offer;
class OfferRepository implements \App\Gateway\OfferGateway
{
    private static array $storage = [];
    private static int $nextId = 1;
    public function publish(Offer $offer): void
    {
        // In-memory implementation, you can store the offer in an array or any other data structure
        if($offer->getId() === null) {
            // reflectively set the protected id property or use a setter if available
            $ref = new \ReflectionObject($offer);
            if ($ref->hasProperty('id')) {
                $prop = $ref->getProperty('id');
                $prop->setAccessible(true);
                $prop->setValue($offer, self::$nextId++);
            }
        }
        self::$storage[$offer->getId()] = $offer;


    }
}