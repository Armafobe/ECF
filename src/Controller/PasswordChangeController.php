<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class PasswordChangeController extends AbstractController
{
    #[Route('/password/change', name: 'password_change')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user->getRoles() == ['ROLE_ADMIN']) {
            return $this->redirectToRoute('admin');
        } elseif ($user->isVerified()) {
            if ($user->getRoles() == ['ROLE_USER']) {
                return $this->redirectToRoute("compte", ['name' => $user->getName()]);
            } else {
                return $this->redirectToRoute("structure", ['name' => $user->getUser()->getName(), 'id' => $user->getId()]);
            }
        }

        $form = $this->createFormBuilder()
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux champs doivent être identiques',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répétez le mot de passe'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sauvegarder le changement'
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setIsVerified(true);
            $entityManager->persist($user);
            $entityManager->flush();

            if ($user->getRoles() == ['ROLE_USER']) {
                return $this->redirectToRoute("compte", ['name' => $user->getName()]);
            } else {
                return $this->redirectToRoute("structure", ['name' => $user->getUser()->getName(), 'id' => $user->getId()]);
            }
        }

        return $this->render('password_change/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
