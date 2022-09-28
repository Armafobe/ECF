<?php

namespace App\Controller;

use App\Entity\Permissions;
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
    #[Route('/haltère-ego/{name}/{id}', name: 'structure', methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, $name, $id, Request $request): Response
    {
        $franchise = $doctrine->getRepository(User::class)->findOneBy(array('name' => $name));

        $structure = $doctrine->getRepository(Structure::class)->findOneBy(array('id' => $id));

        $permissions = $doctrine->getRepository(Permissions::class)->findAll();

        $permission = $structure->getUser()->getPermissions($permissions);

        if ($this->getUser()->getRoles() == ['ROLE_ADMIN']) {
            $id = $doctrine->getRepository(User::class)->findUser($name);
            $user = $doctrine->getRepository(User::class)->findPermissions($id);
            if (isset($user[0])) {
                $permission = $user[0]->getPermissions();
            } else {
                $permission = '';
            }
        } else {
            $permission = $this->getUser()->getPermissions();
        }

        if (!$franchise) {
            return $this->redirectToRoute('connexion');
        }

        return $this->render('structure/index.html.twig', [
            'structure' => $structure,
            'franchise' => $franchise,
            'permissions' => $permission,
        ]);
    }
}
