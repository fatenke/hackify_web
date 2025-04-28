<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(Request $request, UserRepository $userRepository): Response
    {
        $search = $request->query->get('search');        $sort = $request->query->get('sort', 'idUser');
        $direction = $request->query->get('direction', 'asc');
        $status = $request->query->get('status');
        
        $queryBuilder = $userRepository->createQueryBuilder('u');
        
        // Search
        if ($search) {
            $queryBuilder
                ->where('u.nomUser LIKE :search')
                ->orWhere('u.prenomUser LIKE :search')
                ->orWhere('u.emailUser LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        
        // Filter by status
        if ($status) {
            $queryBuilder
                ->andWhere('u.statusUser = :status')
                ->setParameter('status', $status);
        }
        
        // Sorting
        $queryBuilder->orderBy('u.' . $sort, $direction);
        
        $users = $queryBuilder->getQuery()->getResult();
        
        return $this->render('backoffice/admin/admin.html.twig', [
            'users' => $users,
            'currentSort' => $sort,
            'currentDirection' => $direction,
            'currentSearch' => $search,
            'currentStatus' => $status
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

    #[Route('/dashboard/export-pdf', name: 'admin_dashboard_export_pdf')]
    public function exportPdf(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        // Configure Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($options);

        // Generate the HTML for the PDF
        $html = $this->renderView('backoffice/admin/pdf_template.html.twig', [
            'users' => $users,
            'date' => new \DateTime()
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Generate PDF file name
        $fileName = 'users_list_' . date('Y-m-d_H-i-s') . '.pdf';

        // Return the PDF as response
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => ResponseHeaderBag::DISPOSITION_ATTACHMENT . '; filename="' . $fileName . '"'
            ]
        );
    }
}
