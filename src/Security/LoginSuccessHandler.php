<?php

namespace App\Security;

use App\Enum\UserRole;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $user = $token->getUser();
        $roles = $user->getRoleUser();

        // Check if user has ROLE_ADMIN
        if (in_array(UserRole::ADMIN->value, $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('admin_dashboard'));
        }

        // Default redirect for other roles
        return new RedirectResponse($this->urlGenerator->generate('app_profile_show'));
    }
}
