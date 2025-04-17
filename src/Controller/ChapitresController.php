<?php

namespace App\Controller;

use App\Entity\Chapitres;
use App\Form\ChapitresType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chapitres')]
final class ChapitresController extends AbstractController
{
    #[Route('', name: 'app_chapitres_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->redirectToRoute('app_chapitres_new');
    }

    #[Route('/new', name: 'app_chapitres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chapitre = new Chapitres();
        $form = $this->createForm(ChapitresType::class, $chapitre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chapitre);
            $entityManager->flush();

            return $this->redirectToRoute('app_chapitres_show', ['id' => $chapitre->getId()]);
        }

        return $this->render('chapitres/new.html.twig', [
            'chapitre' => $chapitre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chapitres_show', methods: ['GET'])]
    public function show(Chapitres $chapitre): Response
    {
        return $this->render('chapitres/show.html.twig', [
            'chapitre' => $chapitre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chapitres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chapitres $chapitre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChapitresType::class, $chapitre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chapitres_show', ['id' => $chapitre->getId()]);
        }

        return $this->render('chapitres/edit.html.twig', [
            'chapitre' => $chapitre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chapitres_delete', methods: ['POST'])]
    public function delete(Request $request, Chapitres $chapitre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chapitre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($chapitre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chapitres_new');
    }
}
