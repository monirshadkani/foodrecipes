<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\User;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('label')
        ->add('description')
        ->add('hours', IntegerType::class, [
            'label' => 'Hours',
            'attr' => ['min' => 0],
            'mapped' => false,  
        ])
        ->add('minutes', IntegerType::class, [
            'label' => 'Minutes',
            'attr' => ['min' => 0, 'max' => 59],
            'mapped' => false, 
        ])
        ->add('personCount')
        ->add('photo', FileType::class, [
            'label' => 'Photo',
            'mapped' => false, // On ne mappe pas ce champ directement à l'entité
            'required' => false,
        ])
        ->add('recipeIngredients', CollectionType::class, [
            'entry_type' => RecipeIngredientType::class,  
            'allow_add' => true,
            'by_reference' => false,  
            'allow_delete' => true,
            'label' => "Ingrédients",
            'prototype' => true,  
            'prototype_name' => '__name__',  
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
