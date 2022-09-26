<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function show(Request $request, ManagerRegistry $doctrine): Response
    {
        if($_POST){
            $result = json_decode($request->request->get('data'), true);
            $result = $result['data'][0]['value'];
            $users = $doctrine->getRepository(User::class)->findBySearch($result);
            dump($users);
        }
        else {
            $users = $doctrine->getRepository(User::class)->findAll();
        }

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

}
