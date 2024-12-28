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
        
        ->add('ingredients', EntityType::class, [
            'class' => Ingredient::class,
            'choice_label' => 'label',  
            'placeholder' => 'Choisir un ingrédient',  
            
            'choice_attr' => function (Ingredient $ingredient) {
                return ['data-unit' => $ingredient->getUnit()]; 
            },
        ])
       
        ->add('quantity', IntegerType::class, [
            'label' => 'Quantité',
            'required' => false,  
            'attr' => ['min' => 0],  
        ])->add('unitDisplay', TextType::class, [
        'mapped' => false,  
        'label' => 'Unité',
        'attr' => ['readonly' => true],  
    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredient::class,
        ]);
    }
}
