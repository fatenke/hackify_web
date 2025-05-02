<?php




namespace App\Controller;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Dompdf\Dompdf;
use Dompdf\Options;
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
    
            return $this->redirectToRoute('projets_qr', ['id' => $projet->getId()]);
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
    

    #[Route('/backoffice/projets/supprimer/{id}', name: 'supprimer_projet', methods: ['POST'])]
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
        // Get the hackathon ID before removing the project
        $hackathon = $projet->getIdHack();
        $projetId = $projet->getId();
        $csrfTokenName = 'delete'.$projetId;
        $csrfToken = $request->request->get('_token');

        // Log the CSRF token validation attempt
        $this->addFlash('debug', 'Attempting to delete projet with ID: '.$projetId);

        if ($this->isCsrfTokenValid($csrfTokenName, $csrfToken)) {
            try {
                $em->remove($projet);
                $em->flush();
                $this->addFlash('success', 'Projet supprimé avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la suppression du projet: ' . $e->getMessage());
            }
        } else {
            $this->addFlash('error', 'Jeton CSRF invalide. La suppression a été annulée.');
        }
    
        return $this->redirectToRoute('voir_projets', ['id' => $hackathon ? $hackathon->getId_hackathon() : null]);
    }
    #[Route('/technologie/supprimer/{id}', name: 'supprimer_technologie', methods: ['POST'])]
    public function supprimertech(Request $request, Technologies $Technologies, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('supprimer'.$Technologies->getId(), $request->request->get('_token'))) {
            // Check if the technology is used in any project
            $projetsUsingTechnology = $Technologies->getProjets();
            
            if (count($projetsUsingTechnology) > 0) {
                $this->addFlash('error', 'Technologie existe  dans projet');
                return $this->redirectToRoute('voir_technologies_back');
            }
            
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

    #[Route('/technologies/statistiques', name: 'technologies_statistiques')]
    public function technologiesStatistiques(EntityManagerInterface $em): Response
    {
        // Get all technologies
        $technologies = $em->getRepository(Technologies::class)->findAll();
        
        // Get all projects
        $projets = $em->getRepository(Projets::class)->findAll();
        
        // Calculate technology usage statistics
        $techStats = [];
        $totalProjects = count($projets);
        
        // Debug: Log total number of projects
        $this->addFlash('debug', "Total Projects: $totalProjects");
        
        foreach ($technologies as $tech) {
            // Get projects for this technology
            $techProjects = $tech->getProjets();
            $usageCount = count($techProjects);
            $percentage = $totalProjects > 0 ? round(($usageCount / $totalProjects) * 100, 2) : 0;
            
            // Debug: Log details for each technology
            $this->addFlash('debug', "Technology: {$tech->getNomTech()}, Projects: $usageCount");
            
            // Debug: Log project details for this technology
            foreach ($techProjects as $projet) {
                $this->addFlash('debug', "- Projet: {$projet->getNom()}");
            }
            
            $techStats[] = [
                'nom' => $tech->getNomTech(),
                'type' => $tech->getTypeTech(),
                'usageCount' => $usageCount,
                'percentage' => $percentage,
                'projects' => array_map(function($projet) { return $projet->getNom(); }, $techProjects->toArray())
            ];
        }
        
        // Sort technologies by usage percentage in descending order
        usort($techStats, function($a, $b) {
            return $b['percentage'] - $a['percentage'];
        });
        
        return $this->render('backoffice/technologies/statistiques.html.twig', [
            'techStats' => $techStats,
            'totalProjects' => $totalProjects
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
#[Route('/backoffice/projets/pdf', name: 'projets_pdf')]
    public function exportPDF(ProjetsRepository $projetsRepository): Response
    {
        $projets = $projetsRepository->findAll();

        // Configuration Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Générer le HTML à partir du template Twig
        $html = $this->renderView('backoffice/projets/pdf.html.twig', [
            'projets' => $projets,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Retourner le fichier PDF
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="liste_projets.pdf"',
        ]);
    }

    #[Route('/backoffice/technologies/pdf', name: 'technologies_pdf')]
    public function exportTechnologiesPDF(TechnologiesRepository $technologiesRepository): Response
    {
        // Récupérer toutes les technologies
        $technologies = $technologiesRepository->findAll();
    
        // Configuration Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
    
        // Générer le HTML à partir du template Twig
        $html = $this->renderView('backoffice/technologies/pdf.html.twig', [
            'technologies' => $technologies,
        ]);
    
        // Charger le HTML dans Dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');  // Choix de la mise en page (portrait ou paysage)
        $dompdf->render();
    
        // Retourner le fichier PDF
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="liste_technologies.pdf"',
        ]);
    }


    #[Route('/projets/qr/{id}', name: 'projets_qr')]
    public function generateQr(Projets $projet): Response
    {
        $nomProjet = $projet->getNom();
        $messageConfirmation = "Projet : $nomProjet\nConfirmation : Projet ajouté avec succès !";
    
        // Création du QR code avec le message de confirmation
        $qrCode = new QrCode($messageConfirmation);
        $writer = new PngWriter();
        $qrImageData = $writer->write($qrCode)->getString();
        $qrImage = base64_encode($qrImageData); // Encodage en base64 pour l'affichage dans la vue
    
        // Récupérer l'ID du hackathon associé au projet
        $hackathonId = $projet->getIdHack() ? $projet->getIdHack()->getId_hackathon() : null;

        return $this->render('projets/qr_confirmation.html.twig', [
            'qrImage' => $qrImage,
            'projet' => $projet,
            'hackathonId' => $hackathonId,
        ]);
    }
    
    

   
}
