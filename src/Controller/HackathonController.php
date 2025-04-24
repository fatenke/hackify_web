<?php

namespace App\Controller;


use App\Entity\Hackathon;
use App\Entity\Communaute;
use App\Entity\Chat;
use App\Enum\ChatType;
use App\Form\HackathonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HackathonRepository;
use App\Repository\ParticipationRepository;
use App\Entity\Participation;




final class HackathonController extends AbstractController
{
    
    #[Route('hackathon/ajouter', name: 'ajouter_hackathon')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $hackathon = new Hackathon();
        $hackathon->setId_organisateur($this->getUser());
        $form = $this->createForm(HackathonType::class, $hackathon);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the hackathon
            $em->persist($hackathon);
            $em->flush();
            
            // Create a matching community
            $communaute = new Communaute();
            $communaute->setId_hackathon($hackathon);
            $communaute->setNom($hackathon->getNom_hackathon());
            $communaute->setDescription($hackathon->getDescription());
            
            $em->persist($communaute);
            $em->flush();
            
            // Create default chats for the community using the ChatType enum
            $chatTypes = [
                ChatType::ANNOUNCEMENT,
                ChatType::QUESTION,
                ChatType::FEEDBACK,
                ChatType::COACH,
                ChatType::BOT_SUPPORT
            ];

            foreach ($chatTypes as $type) {
                $chat = new Chat();
                $chat->setCommunaute_id($communaute);
                $chat->setNom($type->getLabel());
                $chat->setType($type->value);
                $chat->setDate_creation(new \DateTime());
                $chat->setIs_active(true);
                
                $em->persist($chat);
            }
            
            $em->flush();

            return $this->redirectToRoute('liste_hackathon'); 
        }

        return $this->render('hackathon/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('hackathon', name: 'liste_hackathon')]
    public function liste(EntityManagerInterface $entityManager): Response {
        $currentUser = $this->getUser();
        $hackathons = $entityManager->getRepository(Hackathon::class)->findAll();
        $participations = $currentUser ? $entityManager->getRepository(Participation::class)->findBy(['participant' => $currentUser]) : [];

        // Fetch communities for organizers
        if ($currentUser && in_array('ROLE_ORGANISATEUR', $currentUser->getRoles())) {
            $communautesOrganisateur = $entityManager->getRepository(Communaute::class)
                ->createQueryBuilder('c')
                ->join('c.id_hackathon', 'h')
                ->where('h.id_organisateur = :user')
                ->setParameter('user', $currentUser)
                ->getQuery()
                ->getResult();
        } else {
            $communautesOrganisateur = [];
        }

        // Fetch communities for participants
        if ($currentUser && in_array('ROLE_PARTICIPANT', $currentUser->getRoles())) {
            $communautesParticipant = $entityManager->getRepository(Communaute::class)
                ->createQueryBuilder('c')
                ->join('c.id_hackathon', 'h')
                ->join('h.participations', 'p')
                ->where('p.participant = :user')
                ->setParameter('user', $currentUser)
                ->getQuery()
                ->getResult();
        } else {
            $communautesParticipant = [];
        }

        return $this->render('hackathon/afficher.html.twig', [
            'hackathons' => $hackathons,
            'participations' => $participations,
            'communautesOrganisateur' => $communautesOrganisateur,
            'communautesParticipant' => $communautesParticipant,
        ]);
    }

    #[Route('hackathon/{id}', name:'hackathon_details')]
    public function details($id, HackathonRepository $hackathonRepository, ParticipationRepository $participationRepository): Response
    {
        // Trouver le hackathon par son ID
        $hackathon = $hackathonRepository->find($id);

        // Si le hackathon n'est pas trouvé, rediriger vers la liste des hackathons
        if (!$hackathon) {
            return $this->redirectToRoute('liste_hackathon');
        }
        $participations = $participationRepository->findBy([
            'participant' => $this->getUser(),
            'hackathon' => $hackathon
        ]);
        return $this->render('hackathon/details.html.twig', [
            'hackathon' => $hackathon,
            'participations' => $participations,
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
