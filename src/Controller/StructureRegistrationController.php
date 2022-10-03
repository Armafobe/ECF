<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Form\StructureRegistrationFormType;
use App\Security\Mail;
use App\Security\MailStructure;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class StructureRegistrationController extends AbstractController
{
    #[Route('/inscription/structure', name: 'register_structure')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $structure = new Structure();
        $form = $this->createForm(StructureRegistrationFormType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mail = new Mail();
            $mail->send($form->get('email')->getData(), $form->get('address')->getData(), 'Identifiants de connexion', $form->get('address')->getData(), $form->get('email')->getData(), $form->get('password')->getData());

            $mail_structure = new MailStructure();
            $mail_structure->send($form->get('user')->getData()->getEmail(), $form->get('user')->getData()->getName(), 'Nouvelle structure');

            $structure->setPassword(
                $userPasswordHasher->hashPassword(
                    $structure,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($structure);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('registration/structure.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
