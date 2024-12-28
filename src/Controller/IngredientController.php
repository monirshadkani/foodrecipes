<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\IngredientRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Ingredient;
use App\Form\IngredientType;

class IngredientController extends AbstractController
{
    #[Route('makefood/ingredient', name: 'app_ingredient')]
    public function index(): Response
    {
        return $this->redirectToRoute('ingredient_index');
        return $this->render('ingredient/index.html.twig', [
            'controller_name' => 'IngredientController',
        ]);
    }

    #[Route('makefood/ingredient/list', name: 'ingredient_index')]
    public function list(IngredientRepository $ingredientRepository): Response
    {
        $ingredients = $ingredientRepository->findAll();

        return $this->render('ingredient/list.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('makefood/ingredient/new', name: 'ingredient_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ingredient);
            $entityManager->flush();

            return $this->redirectToRoute('ingredient_index');
        }

        return $this->render('ingredient/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ingredient/{id}/edit', name: 'ingredient_edit')]
    public function edit(Request $request,EntityManagerInterface $entityManager, Ingredient $ingredient): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('ingredient_index');
        }

        return $this->render('ingredient/edit.html.twig', [
            'form' => $form->createView(),
            'ingredient' => $ingredient,
        ]);
    }

    #[Route('/ingredient/{id}/delete', name: 'ingredient_delete')]
    public function delete(Ingredient $ingredient,EntityManagerInterface $entityManager): Response
    {

         
        if (!$ingredient->getRecipes()->isEmpty()) {
            $this->addFlash('error', 'Impossible de supprimer cet ingrédient car il est lié à une ou plusieurs recettes.');

            return $this->redirectToRoute('ingredient_index');
        }
        
        $entityManager->remove($ingredient);
        $entityManager->flush();

        return $this->redirectToRoute('ingredient_index');
    }
}
