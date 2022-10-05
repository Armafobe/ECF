<?php

namespace App\Controller;

use App\Security\MailStructure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $user = $this->getUser();

        switch ($user) {
            case $user->getRoles() == ['ROLE_ADMIN']:
                return $this->redirectToRoute('admin');
            case $user->getRoles() == ['ROLE_USER']:
                return $this->redirectToRoute('compte', ['name' => $user->getName()]);
            case $user->getRoles() == ['ROLE_SUBUSER']:
                return $this->redirectToRoute('structure', ['name' => $user->getUser()->getName(), 'id' => $user->getId()]);
            default:
                return $this->redirectToRoute('connexion');
        }
    }
}