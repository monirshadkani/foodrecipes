<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // Champ pour l'ingrédient (qui sera un choix parmi les ingrédients disponibles)
        ->add('ingredients', EntityType::class, [
            'class' => Ingredient::class,
            'choice_label' => 'label',  // Affichage du nom de chaque ingrédient
            'placeholder' => 'Choisir un ingrédient',  // Option pour le premier champ vide
            
            'choice_attr' => function (Ingredient $ingredient) {
                return ['data-unit' => $ingredient->getUnit()]; // Ajoute data-unit
            },
        ])
        // Champ pour la quantité
        ->add('quantity', IntegerType::class, [
            'label' => 'Quantité',
            'required' => false,  // Quantité peut être vide
            'attr' => ['min' => 0],  // Empêcher les valeurs négatives ou nulles
        ])->add('unitDisplay', TextType::class, [
        'mapped' => false,  // Ce champ n'est pas mappé à une entité
        'label' => 'Unité',
        'attr' => ['readonly' => true],  // Ce champ sera en lecture seule
    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredient::class,
        ]);
    }
}
