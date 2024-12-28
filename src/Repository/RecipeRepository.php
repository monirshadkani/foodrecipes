<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function findByIngredients(array $ingredientIds)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.recipeIngredients', 'ri') // Jointure avec la table intermédiaire RecipeIngredient
            ->innerJoin('ri.ingredients', 'i') // Jointure avec les ingrédients via RecipeIngredient
            ->andWhere('i.id IN (:ingredientIds)') // Filtre par les IDs des ingrédients sélectionnés
            ->setParameter('ingredientIds', $ingredientIds) // On passe les ingrédients sélectionnés
            ->groupBy('r.id') // Groupement des résultats par recette
            ->having('COUNT(DISTINCT i.id) = :ingredientCount') // Vérifie que la recette contient exactement tous les ingrédients
            ->setParameter('ingredientCount', count($ingredientIds)) // Le nombre d'ingrédients sélectionnés
            ->getQuery()
            ->getResult();
    }
}
