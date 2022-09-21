<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Entity\User;
use App\Form\PermissionsFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StructureController extends AbstractController
{
    #[Route('/haltere-ego-{name}/{id}', name: 'structure')]
    public function show(ManagerRegistry $doctrine, $name, $id, Request $request): Response
    {
        $franchise = $doctrine->getRepository(User::class)->findOneBy(array('name' => $name));

        $structure = $doctrine->getRepository(Structure::class)->findOneBy(array('id' => $id));

        if (!$franchise) {
            return $this->redirectToRoute('connexion');
        }

        return $this->render('structure/index.html.twig', [
            'structure' => $structure,
            'franchise' => $franchise,
        ]);
    }
}
