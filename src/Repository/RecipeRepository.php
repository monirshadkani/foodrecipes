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
            ->innerJoin('r.recipeIngredients', 'ri') 
            ->innerJoin('ri.ingredients', 'i') 
            ->andWhere('i.id IN (:ingredientIds)') 
            ->setParameter('ingredientIds', $ingredientIds) 
            ->groupBy('r.id') 
            ->having('COUNT(DISTINCT i.id) = :ingredientCount') 
            ->setParameter('ingredientCount', count($ingredientIds)) 
            ->getQuery()
            ->getResult();
    }

    public function findOneWithComments(int $id)
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.comments', 'c')
            ->addSelect('c')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
