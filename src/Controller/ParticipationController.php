<?php

namespace App\Controller;

use App\Entity\Participation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Hackathon;
use App\Repository\ParticipationRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ParticipationController extends AbstractController
{
    #[Route('hackathon/{id}/participer', name: 'hackathon_participer')]
public function participer(
    Hackathon $hackathon,
    EntityManagerInterface $em
): Response {
    $user = $this->getUser();
    $participation = new Participation();
    $participation->setHackathon($hackathon);
    $participation->setDate_inscription(new \DateTime());
    $participation->setStatut('En attente');
    $participation->setParticipant($user);

    $em->persist($participation);
    $em->flush();


    return $this->redirectToRoute('hackathon_details', ['id' => $hackathon->getId_hackathon()]);
}
#[Route('hackathon/{id}/participants', name: 'voir_participants')]
public function voirParticipants(Hackathon $hackathon, ParticipationRepository $participationRepository): Response
{
    $participations = $participationRepository->findBy(['hackathon' => $hackathon]);

    return $this->render('participation/afficherParticipation.html.twig', [
        'hackathon' => $hackathon,
        'participations' => $participations,
    ]);
}
#[Route('participation/annuler/{id}', name: 'annuler_participation')]
public function annulerParticipation(
    Participation $participation,
    EntityManagerInterface $entityManager
): Response {
    $entityManager->remove($participation);
    $entityManager->flush();

    return $this->redirectToRoute('liste_hackathon'); 
}
#[Route('/participation/{id}/valider', name: 'valider_participation')]
public function accepter(Participation $participation,EntityManagerInterface $entityManager): Response {
    $participation->setStatut('validé');
    $entityManager->flush();

    return $this->redirectToRoute('voir_participants', ['id' => $participation->getHackathon()->getId_hackathon()]);
}

#[Route('/participation/{id}/refuser', name: 'refuser_participation')]
public function refuser(Participation $participation,EntityManagerInterface $entityManager): Response {
    $participation->setStatut('refusé');
    $entityManager->flush();

    return $this->redirectToRoute('voir_participants', ['id' => $participation->getHackathon()->getId_hackathon()]);
}


}
