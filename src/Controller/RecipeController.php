<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class RecipeController extends AbstractController
{
    #[Route('makefood/recipe', name: 'app_recipe')]
    public function index(RecipeRepository $recipeRepository ): Response
    {
        $recipes = $recipeRepository->findAll();
        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
            'recipes' => $recipes,
        ]);
    }

    

    #[Route('makefood/recipes/new', name: 'recipe_new')]
    public function new(Request $request,EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();


        $form = $this->createForm(RecipeType::class, $recipe);

        //$listIngredient = $form->get("ingredients")->getData();
        $form->handleRequest($request);
       

        if ($form->isSubmitted() && $form->isValid()) {
           
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();
        
                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'), // Paramètre à définir
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer les erreurs si nécessaire
                }
        
                $recipe->setPhoto('uploads/photos/' . $newFilename);
            }

            $user = $this->getUser(); 
            $recipe->setUser($user); 
            
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe,
        ]);
    }

    #[Route('makefood/recipes/{id}/edit', name: 'recipe_edit')]
    public function edit(Request $request,EntityManagerInterface $entityManager, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();
        
                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'), // Paramètre à définir
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer les erreurs si nécessaire
                }
        
                $recipe->setPhoto('uploads/photos/' . $newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('recipe/edit.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe,
        ]);
    }

    #[Route('makefood/recipes/{id}/delete', name: 'recipe_delete')]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $entityManager,): Response
    {
        
        $entityManager->remove($recipe);
        $entityManager->flush();

        return $this->redirectToRoute('app_recipe');
    }
}
