<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $validationErrors = [];

        if ($request->isMethod('POST')) {
            $validator = Validation::createValidator();
            $email = $request->request->get('_username');
            $password = $request->request->get('password');

            // Validate email
            $emailConstraints = [
                new Assert\NotBlank(['message' => 'L\'email est obligatoire']),
                new Assert\Email(['message' => 'L\'email "{{ value }}" n\'est pas valide.'])
            ];
            $emailErrors = $validator->validate($email, $emailConstraints);

            // Validate password
            $passwordConstraints = [
                new Assert\NotBlank(['message' => 'Le mot de passe est obligatoire']),
                new Assert\Length([
                    'min' => 8,
                    'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères'
                ])
            ];
            $passwordErrors = $validator->validate($password, $passwordConstraints);

            // Collect all validation errors
            foreach ($emailErrors as $error) {
                $validationErrors['email'][] = $error->getMessage();
            }
            foreach ($passwordErrors as $error) {
                $validationErrors['password'][] = $error->getMessage();
            }

            // If there are validation errors, don't proceed with authentication
            if (count($validationErrors) > 0) {
                return $this->render('security/login.html.twig', [
                    'last_username' => $email,
                    'validation_errors' => $validationErrors,
                ]);
            }
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'validation_errors' => $validationErrors,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
