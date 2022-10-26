<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\Mail;
use App\Security\MailStructure;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription/partenaire', name: 'register_user')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mail = new Mail();
            $mail->send($form->get('email')->getData(), $form->get('name')->getData(), 'Identifiants de connexion', $form->get('email')->getData(), $form->get('email')->getData(), $form->get('password')->getData());
            
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('admin');
        }

        return $this->render('registration/user.html.twig', [
            'registrationForm' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }
}
