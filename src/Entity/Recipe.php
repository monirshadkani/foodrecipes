<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column]
    private ?int $personCount = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    
    /**
     * @var Collection<int, RecipeIngredient>
     */
    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'recipe', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $recipeIngredients;

    #[ORM\ManyToOne(targetEntity:User::class, inversedBy:"recipes")]
    private ?User $user = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'recipe')]
    private Collection $comments;

    

    public function __construct()
    {
       
        $this->recipeIngredients = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPersonCount(): ?int
    {
        return $this->personCount;
    }

    public function setPersonCount(int $personCount): static
    {
        $this->personCount = $personCount;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
   
    $ingredients = new ArrayCollection();
    
        foreach ($this->getRecipeIngredients() as $recipeIngredient) {
            $ingredients->add($recipeIngredient->getIngredients());
        }
    
    return $ingredients;
    }

    public function addIngredient(Ingredient $ingredient, int $quantity): static
    {
        
        foreach ($this->getRecipeIngredients() as $recipeIngredient) {
            if ($recipeIngredient->getIngredients() === $ingredient) {
                return $this; 
            }
        }
    
        
        $recipeIngredient = new RecipeIngredient();
        $recipeIngredient->setRecipe($this);
        $recipeIngredient->setIngredients($ingredient);
        $recipeIngredient->setQuantity($quantity); 
    
        
        $this->addRecipeIngredient($recipeIngredient);
    
        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): static
    {
    
    foreach ($this->getRecipeIngredients() as $recipeIngredient) {
        if ($recipeIngredient->getIngredients() === $ingredient) {
            $this->removeRecipeIngredient($recipeIngredient); 
            break;
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
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setRecipe($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }

        return $this;
    }
}
