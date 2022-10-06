<?php

namespace App\Controller;

use App\Entity\Permissions;
use App\Entity\Structure;
use App\Entity\User;
use App\Form\PermissionsFormType;
use App\Security\Mail;
use App\Security\MailStructure;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StructureController extends AbstractController
{
    #[Route('/haltère-égo/{name}/{id}', name: 'structure')]
    public function show(ManagerRegistry $doctrine, $name, $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $getUser = $this->getUser();

        $franchise = $doctrine->getRepository(User::class)->findOneBy(array('name' => $name));

        $structure = $doctrine->getRepository(Structure::class)->findOneBy(array('id' => $id));

        $activate_form = $this->createFormBuilder()
            ->add('activated', SubmitType::class)
            ->getForm();
        $activate_form->handleRequest($request);

        if ($activate_form->isSubmitted() && $activate_form->isValid()) {
            $structure->setIsActive(!$structure->isIsActive());
            $entityManager->persist($structure);
            $entityManager->flush();
            return $this->redirect($request->getUri());
        }

        $form = $this->createFormBuilder()
            ->add('permissions', EntityType::class, [
                'class' => Permissions::class,
                'choices' => $franchise->getPermissions(),
                'label' => false,
                'expanded' => true,
                'multiple' => true,
                'data' => $structure->getPermissions()
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mail = new Mail();
            $mail->sendPermissions($structure->getEmail(), $structure->getAddress(), 'Nouvelles permissions');

            $mailToUser = new MailStructure();
            $mailToUser->toFranchise($franchise->getEmail(), $franchise->getName(), 'Nouvelles permissions', $structure->getAddress());

            foreach ($form->getData()['permissions'] as $permission_id) {
                $permission = $entityManager->getRepository(Permissions::class)->find($permission_id);
                $structure->addPermission($permission);
            };
            $entityManager->persist($structure);
            $entityManager->flush();
            return $this->redirect($request->getUri());
        }

        if ($this->getUser()->getRoles() == ['ROLE_ADMIN']) {
            $id = $doctrine->getRepository(User::class)->findUser($name);
            $user = $doctrine->getRepository(User::class)->findPermissions($id);
            if (isset($user[0])) {
                $permission = $user[0]->getPermissions();
            } else {
                $permission = '';
            }
        } else {
            $permission = $getUser->getPermissions();
        }

        return $this->render('structure/index.html.twig', [
            'structure' => $structure,
            'franchise' => $franchise,
            'permission' => $permission,
            'user' => $getUser,
            'activate_form' => $activate_form->createView(),
            'form' => $form->createView()
        ]);
    }
}
