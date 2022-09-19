<?php

namespace App\Controller;

use App\Security\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $mail = new Mail();
        $mail->send('arthur_berthou@hotmail.fr', 'Moi', 'Test email');

        return $this->render('home/index.html.twig');
    }
}