<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\IngredientRepository;

class HomeController extends AbstractController
{
    #[Route('/makefood', name: 'app_home')]
    public function index(IngredientRepository $ingredientRepository): Response
    {
        $ingredients = $ingredientRepository->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'ingredients' => $ingredients
        ]);
    }
    
}
