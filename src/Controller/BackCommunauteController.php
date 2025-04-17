<?php

namespace App\Controller;

use App\Entity\Communaute;
use App\Form\CommunauteType;
use App\Repository\CommunauteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice/communaute')]
class BackCommunauteController extends AbstractController
{
    #[Route('/', name: 'app_communaute_back', methods: ['GET'])]
    public function showBack(CommunauteRepository $communauteRepository): Response
    {
        $communautes = $communauteRepository->findAll();

        return $this->render('backoffice/communautes/show.html.twig', [
            'communautes' => $communautes,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_communaute_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Communaute $communaute, CommunauteRepository $communauteRepository): Response
    {
        $form = $this->createForm(CommunauteType::class, $communaute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $communauteRepository->save($communaute, true);

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Communauté modifiée avec succès.',
                ]);
            }

            $this->addFlash('success', 'Communauté modifiée avec succès.');
            return $this->redirectToRoute('app_communaute_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backoffice/communautes/edit.html.twig', [
            'communaute' => $communaute,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_communaute_delete', methods: ['POST'])]
    public function delete(Request $request, Communaute $communaute, CommunauteRepository $communauteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$communaute->getId(), $request->request->get('_token'))) {
            $communauteRepository->remove($communaute, true);
            $this->addFlash('success', 'Communauté supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_communaute_back', [], Response::HTTP_SEE_OTHER);
    }
}