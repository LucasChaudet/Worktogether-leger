<?php

namespace App\Controller\Client;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientBaseController extends AbstractController
{
    protected function getClient(): Client
    {
        /** @var Client $client */
        $client = $this->getUser();

        return $client;
    }
}
