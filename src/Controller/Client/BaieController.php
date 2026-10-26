<?php

namespace App\Controller\Client;

use App\Entity\Baie;
use App\Entity\Client;
use App\Repository\UniteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client/baie')]
final class BaieController extends AbstractController
{

    #[Route(name: 'app_client_baie_index', methods: ['GET'])]
    public function index(UniteRepository $uniteRepository): Response
    {
        $baies = [];
        $nbUnites = [];
        foreach ($uniteRepository->findLoueesParClient($this->getClient()) as $unite) {
            $baie = $unite->getBaie();
            $baies[$baie->getId()] = $baie;
            $nbUnites[$baie->getId()] = ($nbUnites[$baie->getId()] ?? 0) + 1;
        }

        return $this->render('client/baie/index.html.twig', [
            'baies' => $baies,
            'nbUnites' => $nbUnites,
        ]);
    }


    #[Route('/{id}', name: 'app_client_baie_show', methods: ['GET'])]
    public function show(Baie $baie, UniteRepository $uniteRepository): Response
    {
        $mesUnites = [];
        foreach ($uniteRepository->findLoueesParClient($this->getClient()) as $unite) {
            if ($unite->getBaie() === $baie) {
                $mesUnites[$unite->getId()] = true;
            }
        }

        if (!$mesUnites) {
            throw $this->createAccessDeniedException('Vous n\'avez aucune unité dans cette baie.');
        }

        return $this->render('client/baie/show.html.twig', [
            'baie' => $baie,
            'mesUnites' => $mesUnites,
        ]);
    }

    private function getClient(): Client
    {
        /** @var Client $client */
        $client = $this->getUser();

        return $client;
    }
}
