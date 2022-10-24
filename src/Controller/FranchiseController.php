<?php

namespace App\Controller;

use App\Entity\Permissions;
use App\Entity\User;
use App\Security\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FranchiseController extends AbstractController
{
    #[Route('/haltère-égo/{name}', name: 'compte')]
    public function show(Request $request, ManagerRegistry $doctrine, $name, User $user, EntityManagerInterface $entityManager): Response
    {
        $franchise = $doctrine->getRepository(User::class)->findOneBy(array('name' => $name));
        $getUser = $this->getUser();
        $structures = $user->getStructures();
        $permission = $doctrine->getRepository(Permissions::class)->findAll();
        $userPerm = $franchise->getPermissions();

        if (!$getUser) {
            return $this->redirectToRoute('app_login');
        }

        if ($getUser->getRoles() != ['ROLE_ADMIN']) {
            if (!$getUser->isVerified()) {
                return $this->redirectToRoute('password_change');
            }
        }

        $activate_form = $this->createFormBuilder()
            ->add('activated', SubmitType::class)
            ->getForm();
        $activate_form->handleRequest($request);

        if ($activate_form->isSubmitted() && $activate_form->isValid()) {
            $franchise->setIsActive(!$franchise->isIsActive());
            $entityManager->persist($franchise);
            $entityManager->flush();
            if (!$franchise->isIsActive()) {
                foreach ($structures as $structure) {
                    $structure->setIsActive(false);
                    $entityManager->persist($structure);
                    $entityManager->flush();
                }
            }
            return $this->redirect($request->getUri());
        }

        $permissions_form = $this->createFormBuilder()
            ->add('permissions', EntityType::class, [
                    'class' => Permissions::class,
                    'expanded' => true,
                    'multiple' => true,
                    'data' => $franchise->getPermissions($permission)
            ])
            ->getForm();
        $permissions_form->handleRequest($request);

        if ($permissions_form->isSubmitted() && $permissions_form->isValid()) {
            $mail = new Mail();
            $mail->sendPermissions($franchise->getEmail(), $franchise->getName(), 'Nouvelles permissions');

            foreach ($permissions_form->getData()['permissions'] as $permission_id) {
                $permission = $entityManager->getRepository(Permissions::class)->find($permission_id);
                $franchise->addPermission($permission);
            };
            $entityManager->persist($franchise);
            $entityManager->flush();
            return $this->redirect($request->getUri());
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
            'permission' => $userPerm,
            'permissions_form' => $permissions_form->createView(),
            'activate_form' => $activate_form->createView()
        ]);
    }
}
