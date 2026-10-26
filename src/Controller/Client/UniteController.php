<?php

namespace App\Controller\Client;

use App\Entity\Client;
use App\Entity\Unite;
use App\Form\UniteType;
use App\Repository\UniteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/client/unite')]
final class UniteController extends AbstractController
{
    #[Route(name: 'app_client_unite_index', methods: ['GET'])]
    public function index(#[CurrentUser] Client $client, UniteRepository $uniteRepository): Response
    {
        return $this->render('client/unite/index.html.twig', [
            'unites' => $uniteRepository->findLoueesParClient($client),
        ]);
    }

    #[Route('/{id}', name: 'app_client_unite_show', methods: ['GET'])]
    public function show(#[CurrentUser] Client $client, Unite $unite): Response
    {
        $this->verifierProprietaire($client, $unite);

        return $this->render('client/unite/show.html.twig', [
            'unite' => $unite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_unite_edit', methods: ['GET', 'POST'])]
    public function edit(#[CurrentUser] Client $client, Request $request, Unite $unite, EntityManagerInterface $entityManager): Response
    {
        $this->verifierProprietaire($client, $unite);

        $form = $this->createForm(UniteType::class, $unite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'unité '.$unite->getEmplacement().' a été modifiée.');

            return $this->redirectToRoute('app_client_unite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/unite/edit.html.twig', [
            'unite' => $unite,
            'form' => $form,
        ]);
    }

    private function verifierProprietaire(Client $client, Unite $unite): void
    {
        if ($unite->getCommandeEnCours()?->getClient() !== $client) {
            throw $this->createAccessDeniedException('Cette unité ne vous appartient pas.');
        }
    }
}
