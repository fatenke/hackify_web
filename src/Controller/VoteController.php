<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\VoteRepository;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoteController extends AbstractController
{
  #[Route('/vote/add/{idProjet}', name: 'vote_add')]
  public function addVote(Request $request, VoteRepository $voteRepo, ProjetRepository $projectRepo, int $idProjet): Response
  {
    $vote = new Vote();

    // Pre-fill project field if exists
    $project = $projectRepo->find($idProjet);
    if ($project) {
      $vote->setIdProjet($project);
    }

    // Create the form and handle the pre-set project
    $form = $this->createForm(VoteType::class, $vote);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $voteRepo->save($vote, true);
      return $this->redirectToRoute('project_list');
    }

    return $this->render('addVote.html.twig', [
      'form' => $form->createView(),
    ]);
  }



  #[Route('/vote/list', name: 'vote_list')]
  public function list(EntityManagerInterface $entityManager): Response
  {
    $votes = $entityManager->getRepository(Vote::class)->findAll();

    return $this->render('listVote.html.twig', [
      'votes' => $votes
    ]);
  }

  #[Route('/vote/edit/{id}', name: 'vote_edit')]
  public function edit(Request $request, EntityManagerInterface $entityManager, int $id): Response
  {
    $vote = $entityManager->getRepository(Vote::class)->find($id);

    if (!$vote) {
      throw $this->createNotFoundException('Vote not found');
    }

    $form = $this->createForm(VoteType::class, $vote);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();
      $this->addFlash('success', 'Vote updated successfully!');
      return $this->redirectToRoute('vote_list');
    }

    return $this->render('editVote.html.twig', [
      'form' => $form->createView(),
      'vote' => $vote
    ]);
  }

  #[Route('/vote/delete/{id}', name: 'vote_delete')]
  public function delete(EntityManagerInterface $entityManager, int $id): Response
  {
    $vote = $entityManager->getRepository(Vote::class)->find($id);

    if ($vote) {
      $entityManager->remove($vote);
      $entityManager->flush();
      $this->addFlash('success', 'Vote deleted successfully!');
    }

    return $this->redirectToRoute('vote_list');
  }
}
