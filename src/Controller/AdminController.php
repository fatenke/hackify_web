<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\HackathonRepository;


#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        
        return $this->render('backoffice/admin/admin.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/user/edit/{id}', name: 'admin_user_edit')]
    public function editUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $user->setNomUser($request->request->get('nomUser'));
            $user->setPrenomUser($request->request->get('prenomUser'));
            $user->setEmailUser($request->request->get('emailUser'));
            $user->setTelUser((int)$request->request->get('telUser'));
            $user->setAdresseUser($request->request->get('adresseUser'));
            $user->setStatusUser($request->request->get('statusUser'));
            
            $entityManager->flush();
            
            $this->addFlash('success', 'User updated successfully');
            return $this->redirectToRoute('admin_dashboard');
        }
        
        return $this->render('backoffice/admin/edit_user.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/user/delete/{id}', name: 'admin_user_delete')]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();
        
        $this->addFlash('success', 'User deleted successfully');
        return $this->redirectToRoute('admin_dashboard');
    }
    #[Route('/hackathons', name: 'admin_hackathon_list')]
    public function index(HackathonRepository $repo): Response
{
    $hackathons = $repo->findAll(); // récupère tous les hackathons

    return $this->render('backoffice/hackathon/afficher.html.twig', [
        'hackathons' => $hackathons, // passe les données à Twig
    ]);
}

#[Route('/admin/hackathon/{id}/delete', name: 'admin_delete_hackathon', methods: ['POST'])]
public function delete(Hackathon $hackathon, EntityManagerInterface $em, Request $request): Response
{
    if ($this->isCsrfTokenValid('delete' . $hackathon->getId(), $request->request->get('_token'))) {
        $em->remove($hackathon);
        $em->flush();
        $this->addFlash('success', 'Hackathon supprimé avec succès.');
    }

    return $this->redirectToRoute('admin_hackathon_list');
}


}
