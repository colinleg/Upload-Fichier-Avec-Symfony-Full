<?php

namespace App\Controller;

use App\Repository\LegumeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegumesController extends AbstractController
{
    private $legumeRepository;
    
    public function __construct(LegumeRepository $legumeRepository)
    {
        $this->legumeRepository = $legumeRepository;
    }

    #[Route('/legumes', name: 'legumes')]
    public function index(): Response
    {

        $legumes = $this->legumeRepository->findAll();


        return $this->render('legumes/index.html.twig', [
            'controller_name' => 'LegumesController',
            'legumes' => $legumes
        ]);
    }
}
