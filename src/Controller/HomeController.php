<?php

namespace App\Controller;

use App\Entity\Campaign;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $campaigns = $doctrine->getRepository(Campaign::class)->findAll();

        return $this->render('home/index.html.twig', [
            'campaigns' => $campaigns,
        ]);
    }
}
