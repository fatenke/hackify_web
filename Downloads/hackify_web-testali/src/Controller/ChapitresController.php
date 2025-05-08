<?php

namespace App\Controller;

use App\Entity\Chapitres;
use App\Form\ChapitresType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ChapitresRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Knp\Component\Pager\PaginatorInterface; // Importation de la pagination

#[Route('/chapitres')]
final class ChapitresController extends AbstractController
{
    // Route pour afficher la liste des chapitres avec pagination
    #[Route('/', name: 'app_chapitres_index', methods: ['GET'])]
    public function index(ChapitresRepository $chapitresRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $chapitresRepository->createQueryBuilder('c');
    
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5 // ✅ 5 chapitres par page
        );
    
        return $this->render('chapitres/index.html.twig', [
            'pagination' => $pagination,
        ]);
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

            return $this->redirectToRoute('app_chapitres_index', ['id' => $chapitre->getId()]);
        }

        return $this->render('chapitres/new.html.twig', [
            'chapitre' => $chapitre,
            'form' => $form->createView(),
        ]);
    }

    // Afficher les favoris
    #[Route('/mes-favoris', name: 'chapitre_favoris_liste')]
    public function favoris(SessionInterface $session, ChapitresRepository $repo): Response
    {
        $ids = $session->get('chapitre_favori', []);
        $chapitres = $repo->findBy(['id' => $ids]);

        return $this->render('chapitres/favoris.html.twig', [
            'chapitres' => $chapitres,
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
        if ($this->isCsrfTokenValid('delete' . $chapitre->getId(), $request->get('_token'))) {
            $entityManager->remove($chapitre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chapitres_index');
    }

    // Ajouter/Retirer un chapitre des favoris
    #[Route('/{id}/favori', name: 'chapitre_toggle_favori')]
    public function toggleFavori(int $id, SessionInterface $session, ChapitresRepository $repo): Response
    {
        $chapitre = $repo->find($id);
        if (!$chapitre) {
            throw $this->createNotFoundException('Chapitre non trouvé.');
        }

        $favoris = $session->get('chapitre_favori', []);

        if (in_array($id, $favoris)) {
            $favoris = array_diff($favoris, [$id]);
        } else {
            $favoris[] = $id;
        }

        $session->set('chapitre_favori', $favoris);

        return $this->redirectToRoute('app_chapitres_index');
    }

    /*#[Route('/{titre}/export', name: 'chapitre_export')]
    public function export(string $titre, EntityManagerInterface $em): Response
    {
        $chapitre = $em->getRepository(Chapitres::class)->findOneBy(['titre' => $titre]);

        if (!$chapitre) {
            throw $this->createNotFoundException('Chapitre non trouvé.');
        }

        $filePath = $chapitre->getUrlFichier();
        $format = $chapitre->getFormatFichier(); // ex: pdf, mp4, jpg

        if (!$filePath || !file_exists($filePath)) {
            throw $this->createNotFoundException('Fichier non trouvé.');
        }

        return new BinaryFileResponse($filePath, 200, [
            'Content-Type' => mime_content_type($filePath),
            'Content-Disposition' => ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        ]);
    }*/
}
