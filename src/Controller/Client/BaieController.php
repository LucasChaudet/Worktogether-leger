<?php

namespace App\Controller\Client;

use App\Entity\Baie;
use App\Entity\Client;
use App\Repository\BaieRepository;
use App\Repository\UniteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/client/baie')]
final class BaieController extends AbstractController
{
    #[Route(name: 'app_client_baie_index', methods: ['GET'])]
    public function index(#[CurrentUser] Client $client, BaieRepository $baieRepository): Response
    {
        return $this->render('client/baie/index.html.twig', [
            'baies' => $baieRepository->findLoueesParClient($client),
        ]);
    }

    #[Route('/{id}', name: 'app_client_baie_show', methods: ['GET'])]
    public function show(#[CurrentUser] Client $client, Baie $baie, UniteRepository $uniteRepository): Response
    {
        $mesUnites = $uniteRepository->findLoueesParClient($client, $baie);

        if (!$mesUnites) {
            throw $this->createAccessDeniedException('Vous n\'avez aucune unité dans cette baie.');
        }

        return $this->render('client/baie/show.html.twig', [
            'baie' => $baie,
            'mesUnites' => $mesUnites,
        ]);
    }
}
