<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FranchiseController extends AbstractController
{
    #[Route('/haltere-ego-{name}', name: 'compte')]
    public function show(ManagerRegistry $doctrine, $name, User $user): Response
    {
        $franchise = $doctrine->getRepository(User::class)->findOneBy(array('name' => $name));
        $getUser = $this->getUser();
        $structures = $user->getStructures();
        $permissions = $franchise->getPermissions();

        if ($getUser->getRoles() != ['ROLE_ADMIN'] && $name != $getUser->getName()) {
            return $this->redirectToRoute('compte', ['name' => $getUser->getName()]);
        }

//        if (!$franchise) {
//            return $this->redirectToRoute('connexion');
//        }

        return $this->render('franchise/index.html.twig', [
            'structures' => $structures,
            'franchise' => $franchise,
            'user' => $getUser,
            'permissions' => $permissions
        ]);
    }
}
