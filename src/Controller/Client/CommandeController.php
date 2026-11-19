<?php

namespace App\Controller\Client;


use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\OffreRepository;
use App\Security\Voter\CommandeVoter;
use App\Service\CommandeService;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/new', name: 'app_client_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommandeService $commandeService, OffreRepository $offreRepository): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($commandeService->passerCommande($commande, $this->getClient())) {
                $this->addFlash('success', 'Votre commande a bien été enregistrée.');

                return $this->redirectToRoute('app_client_commande_show', ['id' => $commande->getId()], Response::HTTP_SEE_OTHER);
            }

            $form->addError(new FormError('Il ne reste pas assez d\'unités libres qui se suivent pour cette offre.'));
        }

        return $this->render('client/commande/new.html.twig', [
            'form' => $form,
            'offres' => $offreRepository->findBy([], ['nbUnites' => 'ASC']),
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
