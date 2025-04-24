<?php

namespace App\Controller;

use App\Entity\Hackathon;
use App\Entity\Projets;
use App\Entity\Technologies;
use App\Form\ProjetsType;
use App\Form\ProjetsTypeback;
use App\Form\TechnologiesType;
use App\Repository\ProjetsRepository;
use App\Repository\TechnologiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjetsController extends AbstractController
{
    #[Route('/projets', name: 'app_projets')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // 1) Create a new Projet and set your defaults
        $projet = new Projets();
        $projet->setStatut('en cours');
        $projet->setPriorite('faible');
        
        // 2) Build the form around that pre‑populated entity
        $form = $this->createForm(ProjetsType::class, $projet);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projet);
            $em->flush();
    
            return $this->redirectToRoute('app_projets');
        }
    
        // 3) Render — the form will show your default values
        return $this->render('projets/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('hackathon/{id}/projets', name: 'voir_projets')]
    public function voirProjets(Hackathon $hackathon, ProjetsRepository $projets_repository): Response
    {
        $projets = $projets_repository->findBy(['id_hack' => $hackathon]);
        
        return $this->render('projets/afficherProjets.html.twig', [
            'hackathon' => $hackathon,
            'projets' => $projets,
        ]);
    }
    #[Route('backoffice/projets', name: 'voir_projets_back')]
    public function voirProjetsback(Request $request, ProjetsRepository $projets_repository): Response
    {
        $nom = $request->query->get('nom');
        $statut = $request->query->get('statut');
        $priorite = $request->query->get('priorite');
    
        $queryBuilder = $projets_repository->createQueryBuilder('p');
    
        if ($nom) {
            $queryBuilder->andWhere('p.nom LIKE :nom')
                         ->setParameter('nom', '%' . $nom . '%');
        }
    
        if ($statut) {
            $queryBuilder->andWhere('p.statut = :statut')
                         ->setParameter('statut', $statut);
        }
    
        if ($priorite) {
            $queryBuilder->andWhere('p.priorite = :priorite')
                         ->setParameter('priorite', $priorite);
        }
    
        $projets = $queryBuilder->getQuery()->getResult();
    
        return $this->render('backoffice/projets/index.html.twig', [
            'projets' => $projets,
        ]);
    }
    #[Route('backoffice/technologies', name: 'voir_technologies_back')]
    public function voirTechnologiesback(Request $request, TechnologiesRepository $technologies_repository): Response
    {
        $nom = $request->query->get('nom');
        $type = $request->query->get('type');
        $complexite = $request->query->get('complexite');
    
        $queryBuilder = $technologies_repository->createQueryBuilder('t');
    
        if ($nom) {
            $queryBuilder->andWhere('t.nom_tech LIKE :nom')
                         ->setParameter('nom', '%' . $nom . '%');
        }
    
        if ($type) {
            $queryBuilder->andWhere('t.type_tech = :type')
                         ->setParameter('type', $type);
        }
    
        if ($complexite) {
            $queryBuilder->andWhere('t.complexite = :complexite')
                         ->setParameter('complexite', $complexite);
        }
    
        $technologies = $queryBuilder->getQuery()->getResult();
    
        return $this->render('backoffice/technologies/index.html.twig', [
            'projets' => $technologies,
        ]);
    }
    

    #[Route('/projets/supprimer/{id}', name: 'supprimer_projet', methods: ['POST'])]
    public function supprimer(Request $request, Projets $projet, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('supprimer'.$projet->getId(), $request->request->get('_token'))) {
            $em->remove($projet);
            $em->flush();
        }
    
        return $this->redirectToRoute('voir_projets_back');
    }
    #[Route('/projets/supprimer/{id}', name: 'supprimer_projet_front', methods: ['POST'])]
    public function supprimerfront(Request $request, Projets $projet, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('supprimerfront'.$projet->getId(), $request->request->get('_token'))) {
            $em->remove($projet);
            $em->flush();
        }
    
        return $this->redirectToRoute('voir_projets');
    }
    #[Route('/technologie/supprimer/{id}', name: 'supprimer_technologie', methods: ['POST'])]
    public function supprimertech(Request $request, Technologies $Technologies, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('supprimer'.$Technologies->getId(), $request->request->get('_token'))) {
            $em->remove($Technologies);
            $em->flush();
        }
    
        return $this->redirectToRoute('voir_technologies_back');
    }
    #[Route('/projets/modifier/{id}', name: 'modifier_projet')]
public function update(Request $request, Projets $projet, EntityManagerInterface $em): Response
{
    $form = $this->createForm(ProjetsTypeback::class, $projet);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('voir_projets_back');
    }

    return $this->render('backoffice/projets/update.html.twig', [
        'form' => $form->createView(),
        'projet' => $projet,
    ]);
}
#[Route('/technologies/ajouter', name: 'ajouter_technologie')]
public function ajouterTechnologie(Request $request, EntityManagerInterface $em): Response
{
    $technologie = new Technologies();
    $form = $this->createForm(TechnologiesType::class, $technologie);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($technologie);
        $em->flush();

        return $this->redirectToRoute('voir_technologies_back');
    }

    return $this->render('backoffice/technologies/addtech.html.twig', [
        'form' => $form->createView(),
    ]);
}
#[Route('/technologies/modifier/{id}', name: 'modifier_technologie')]
public function updatetech(Request $request, Technologies $projet, EntityManagerInterface $em): Response
{
    $form = $this->createForm(TechnologiesType::class, $projet);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('voir_technologies_back');
    }

    return $this->render('backoffice/technologies/update.html.twig', [
        'form' => $form->createView(),
        'projet' => $projet,
    ]);
}

#[Route('/projets/modifier_front/{id}', name: 'modifier_projet_front')]
public function updatefront(Request $request, Projets $projet, EntityManagerInterface $em): Response
{
    $form = $this->createForm(ProjetsType::class, $projet);
    $form->handleRequest($request);

    // if you really want to force these defaults on every save:
    $projet->setStatut('en cours');
    $projet->setPriorite('faible');

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();

        return $this->redirectToRoute('voir_projets', [
            'id' => $projet->getIdHack()->getIdHackathon(),
        ]);
    }

    return $this->render('projets/update.html.twig', [
        'form'    => $form->createView(),
        'projet'  => $projet,
    ]);
}

}
