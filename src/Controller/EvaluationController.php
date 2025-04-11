<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Form\EvaluationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvaluationController extends AbstractController
{
  #[Route('/evaluation/add', name: 'evaluation_add')]
  public function add(Request $request, EntityManagerInterface $entityManager): Response
  {
    $evaluation = new Evaluation();
    $form = $this->createForm(EvaluationType::class, $evaluation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($evaluation);
      $entityManager->flush();

      return $this->redirectToRoute('evaluation_add');
    }

    return $this->render('addEvaluation.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  #[Route('/evaluation/list', name: 'evaluation_list')]
  public function list(EntityManagerInterface $entityManager): Response
  {
    // Get all evaluations along with their associated votes
    $evaluations = $entityManager->getRepository(Evaluation::class)
      ->findBy([], ['id' => 'ASC']); // Retrieve all evaluations

    // Optionally, load votes for each evaluation. If you have a lot of data, consider using a JOIN query to improve performance.
    $evaluationVoteMap = [];
    foreach ($evaluations as $evaluation) {
      // Get the collection of votes for this evaluation
      $votes = $evaluation->getVotes(); // This is a PersistentCollection of Vote objects
      $evaluationVoteMap[$evaluation->getId()] = $votes;
    }

    return $this->render('listEvaluation.html.twig', [
      'evaluations' => $evaluations, // Pass the evaluations with votes to the template
      'evaluationVoteMap' => $evaluationVoteMap, // Optionally, map votes for easy access in the template
    ]);
  }

  #[Route('/evaluation/edit/{id}', name: 'evaluation_edit')]
  public function edit(Request $request, EntityManagerInterface $entityManager, int $id): Response
  {
    $evaluation = $entityManager->getRepository(Evaluation::class)->find($id);

    if (!$evaluation) {
      throw $this->createNotFoundException('Evaluation not found');
    }

    $form = $this->createForm(EvaluationType::class, $evaluation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();
      $this->addFlash('success', 'Evaluation updated successfully!');
      return $this->redirectToRoute('evaluation_list');
    }

    return $this->render('editEvaluation.html.twig', [
      'form' => $form->createView(),
      'evaluation' => $evaluation
    ]);
  }

  #[Route('/evaluation/delete/{id}', name: 'evaluation_delete')]
  public function delete(EntityManagerInterface $entityManager, int $id): Response
  {
    $evaluation = $entityManager->getRepository(Evaluation::class)->find($id);

    if ($evaluation) {
      $entityManager->remove($evaluation);
      $entityManager->flush();
      $this->addFlash('success', 'Evaluation deleted successfully!');
    }

    return $this->redirectToRoute('evaluation_list');
  }
}
