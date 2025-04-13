<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Hackathon;
use App\Repository\ParticipationRepository;

final class ParticipationController extends AbstractController
{
    #[Route('/hackathon/{id}/participer', name: 'hackathon_participer')]
public function participer(
    Hackathon $hackathon,
    EntityManagerInterface $em
): Response {
    $participation = new Participation();
    $participation->setHackathon($hackathon);
    $participation->setDateParticipation(new \DateTime());

    $em->persist($participation);
    $em->flush();

    $this->addFlash('success', 'Vous êtes inscrit(e) à ce hackathon !');

    return $this->redirectToRoute('hackathon_details', ['id' => $hackathon->getId()]);
}
#[Route('/hackathon/{id}/participants', name: 'voir_participants')]
public function voirParticipants(Hackathon $hackathon, ParticipationRepository $participationRepository): Response
{
    $participations = $participationRepository->findBy(['hackathon' => $hackathon]);

    return $this->render('participation/afficherParticipation.html.twig', [
        'hackathon' => $hackathon,
        'participations' => $participations,
    ]);
}
}
