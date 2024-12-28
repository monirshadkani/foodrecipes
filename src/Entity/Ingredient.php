<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $unit = null;


    /**
     * @var Collection<int, RecipeIngredient>
     */
    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'ingredients')]
    private Collection $recipeIngredients;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        
        $recipes = new ArrayCollection();
        
        foreach ($this->getRecipeIngredients() as $recipeIngredient) {
            $recipes->add($recipeIngredient->getRecipe());
        }

        return $recipes;
    }

    public function addRecipe(Recipe $recipe, int $quantity): static
    {
        
        foreach ($this->getRecipeIngredients() as $recipeIngredient) {
            if ($recipeIngredient->getRecipe() === $recipe) {
                return $this; 
            }
        }

       
        $recipeIngredient = new RecipeIngredient();
        $recipeIngredient->setRecipe($recipe);
        $recipeIngredient->setIngredients($this); 
        $recipeIngredient->setQuantity($quantity); 

        
        $recipe->addRecipeIngredient($recipeIngredient);

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        
        foreach ($this->getRecipeIngredients() as $recipeIngredient) {
            if ($recipeIngredient->getRecipe() === $recipe) {
                $this->recipeIngredients->removeElement($recipeIngredient); 
                $recipeIngredient->setIngredients(null); 
                $recipeIngredient->setRecipe(null); 
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setIngredients($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getIngredients() === $this) {
                $recipeIngredient->setIngredients(null);
            }
        }

        return $this;
    }
}
