<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FranchiseController extends AbstractController
{
    public function permissions()
    {

    }

    #[Route('/haltere-ego-{name}', name: 'compte')]
    public function show(ManagerRegistry $doctrine, $name, User $user): Response
    {
        $franchise = $doctrine->getRepository(User::class)->findOneBy(array('name' => $name));
        $getname = $this->getUser()->getName();
        $structures = $user->getStructures();
        $permissions = $franchise->getPermissions();

        if ($name != $getname) {
            return $this->redirectToRoute('compte', ['name' => $getname]);
        }

        if (!$franchise) {
            return $this->redirectToRoute('connexion');
        }

        return $this->render('franchise/index.html.twig', [
            'structures' => $structures,
            'franchise' => $franchise,
            'permissions' => $permissions
        ]);
    }
}
