<?php

namespace App\Controller;


use App\Entity\Hackathon;
use App\Form\HackathonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HackathonRepository;
use App\Repository\ParticipationRepository;
use App\Entity\Chat ;
use App\Entity\Communaute ;
use App\Form\CommunauteType ;





final class HackathonController extends AbstractController
{
    /*#[Route('/hackathon', name: 'app_hackathon')]
    public function index(): Response
    {
        return $this->render('hackathon/index.html.twig', [
            'controller_name' => 'HackathonController',
        ]);
    }*/
    #[Route('/hackathon/ajouter', name: 'ajouter_hackathon')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $hackathon = new Hackathon();
        $form = $this->createForm(HackathonType::class, $hackathon);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->getConnection()->beginTransaction();
            try {
                // Persist Hackathon
                $em->persist($hackathon);
                $em->flush();

                // Create associated Communaute
                $communaute = new Communaute();
                $communauteData = [
                    'nom' => $hackathon->getNomHackathon(),
                    'description' => $hackathon->getDescription(),
                    'id_hackathon' => $hackathon,
                    'is_active' => true,
                ];

                // Validate Communaute data using form
                $communauteForm = $this->createForm(CommunauteType::class, $communaute);
                $communauteForm->submit($communauteData);

                if ($communauteForm->isValid()) {
                    $communaute->setDate_creation(new \DateTime());
                    $em->persist($communaute);

                    // Create 5 default chats
                    $chatTypes = ['ANNOUNCEMENT', 'QUESTION', 'FEEDBACK', 'COACH', 'BOT_SUPPORT'];
                    $chatNames = ['Announcements', 'Questions', 'Feedback', 'Coaching', 'Bot Support'];

                    foreach ($chatTypes as $index => $type) {
                        $chat = new Chat();
                        $chat->setCommunaute_id($communaute);
                        $chat->setNom($chatNames[$index]);
                        $chat->setType($type);
                        $chat->setDate_creation(new \DateTime());
                        $chat->setIs_active(true);
                        $em->persist($chat);
                    }

                    $em->flush();
                    $em->getConnection()->commit();

                    $this->addFlash('success', 'Hackathon and associated community created successfully.');
                    return $this->redirectToRoute('liste_hackathon');
                }

                // If Communaute form is invalid, rollback
                throw new \Exception('Invalid community data: ' . $communauteForm->getErrors(true));
            } catch (\Exception $e) {
                $em->getConnection()->rollBack();
                $this->addFlash('error', 'Failed to create hackathon and community: ' . $e->getMessage());
                return $this->render('hackathon/ajouter.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        return $this->render('hackathon/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    #[Route('hackathon', name: 'liste_hackathon')]
    public function liste(
    HackathonRepository $hackathonRepository,
    ParticipationRepository $participationRepository
    ): Response {
    $hackathons = $hackathonRepository->findAll();
    $participations = $participationRepository->findAll();

    return $this->render('hackathon/afficher.html.twig', [
        'hackathons' => $hackathons,
        'participations' => $participations
    ]);
}

    #[Route('hackathon/{id}', name:'hackathon_details')]
    public function details($id, HackathonRepository $hackathonRepository): Response
    {
        // Trouver le hackathon par son ID
        $hackathon = $hackathonRepository->find($id);

        // Si le hackathon n'est pas trouvé, rediriger vers la liste des hackathons
        if (!$hackathon) {
            return $this->redirectToRoute('hackathons_index');
        }

        return $this->render('hackathon/details.html.twig', [
            'hackathon' => $hackathon,
        ]);
    }
    
    
    #[Route('hackathon/modifier/{id}', name: 'modifier_hackathon')]
    public function modifier(Request $request, Hackathon $hackathon, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(HackathonType::class, $hackathon);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
    
            return $this->redirectToRoute('liste_hackathon');
        }
    
        return $this->render('hackathon/modifier.html.twig', [
            'form' => $form->createView(),
            'hackathon' => $hackathon,
        ]);
    }
    #[Route('/hackathon/supprimer/{id}', name: 'supprimer_hackathon', methods: ['POST'])]
    public function supprimer(Request $request, Hackathon $hackathon, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('supprimer'.$hackathon->getId_hackathon(), $request->request->get('_token'))) {
            $em->remove($hackathon);
            $em->flush();
        }
    
        return $this->redirectToRoute('liste_hackathon');
    }

    

    

}
