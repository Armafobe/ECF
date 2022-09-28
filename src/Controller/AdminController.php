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
        if ($_POST) {
            $result = json_decode($request->request->get('data'), true);
            $result = $result['data'][0]['value'];
            $users = $doctrine->getRepository(User::class)->findBySearch($result);
            dump($users);
        } else {
            $users = $doctrine->getRepository(User::class)->orderByName();
        }

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/filter/active', name: 'active')]
    public function active(Request $request, ManagerRegistry $doctrine): Response
    {
        if ($_POST) {
            $users = $doctrine->getRepository(User::class)->findBy(array('isActive' => true));
            dump($users);
        } else {
            $users = $doctrine->getRepository(User::class)->findAll();
        }

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/filter/inactive', name: 'inactive')]
    public function inactive(Request $request, ManagerRegistry $doctrine): Response
    {
        if ($_POST) {
            $users = $doctrine->getRepository(User::class)->findBy(array('isActive' => false));
            dump($users);
        } else {
            $users = $doctrine->getRepository(User::class)->findAll();
        }

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }
}
