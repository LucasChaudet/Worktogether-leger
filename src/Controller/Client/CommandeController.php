<?php

namespace App\Controller\Client;


use App\Entity\Commande;
use App\Security\Voter\CommandeVoter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client/commande')]
final class CommandeController extends ClientBaseController
{
    #[Route(name: 'app_client_commande_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('client/commande/index.html.twig', [
            'commandes' => $this->getClient()->getCommandes(),
        ]);
    }

    #[Route('/{id}', name: 'app_client_commande_show', methods: ['GET'])]
    #[IsGranted(CommandeVoter::VIEW, 'commande')]
    public function show(Commande $commande): Response
    {
        return $this->render('client/commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }
}
