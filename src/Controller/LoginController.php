<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils, #[CurrentUser] ?User $user): Response
    {
        $user = $this->getUser();

        if ($user) {
            if ($user->getRoles() == ['ROLE_ADMIN']) {
                return $this->redirectToRoute('admin');
            } else if ($user->getRoles() == ['ROLE_USER']) {
                return $this->redirectToRoute('compte', ['name' => $user->getName()]);
            } else {
                return $this->redirectToRoute('structure', ['name' => $user->getUser()->getName(), 'id' => $user->getId()]);
            }
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'user' => $user
        ]);
    }
}
