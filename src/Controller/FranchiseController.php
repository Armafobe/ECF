<?php

namespace App\Controller;

use App\Entity\Permissions;
use App\Entity\User;
use App\Form\PermissionsFormType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FranchiseController extends AbstractController
{
    #[Route('/haltere-ego-{name}', name: 'compte')]
    public function show(Request $request, ManagerRegistry $doctrine, $name, User $user): Response
    {
        $franchise = $doctrine->getRepository(User::class)->findOneBy(array('name' => $name));
        $getUser = $this->getUser();
        $structures = $user->getStructures();
        $permissions = $doctrine->getRepository(Permissions::class)->findAll();

        $form = $this->createFormBuilder()
            ->add('permissions', EntityType::class, [
                    'class' => Permissions::class,
                    'expanded' => true,
                    'multiple' => true,
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->get('permissions')->getViewData()
            ;
        }

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
            'permissions' => $permissions,
            'form' => $form->createView()
        ]);
    }
}
