<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\User;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('label')
        ->add('description')
        ->add('duration')
        ->add('personCount')
        ->add('user', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'username', // Affiche le nom d'utilisateur
            'placeholder' => 'Choisir un utilisateur',
            'required' => false, // L'utilisateur peut être nul
            'attr' => ['class' => 'user-select']
        ])
        ->add('photo', FileType::class, [
            'label' => 'Photo',
            'mapped' => false, // On ne mappe pas ce champ directement à l'entité
            'required' => false,
        ])
        ->add('recipeIngredients', CollectionType::class, [
            'entry_type' => RecipeIngredientType::class,  // Formulaire pour RecipeIngredient
            'allow_add' => true,
            'by_reference' => false,  // Important pour que la collection soit mise à jour
            'allow_delete' => true,
            'label' => "Ingrédients",
            'prototype' => true,  // Pour générer le prototype d'ingrédient
            'prototype_name' => '__name__',  // Nom utilisé dans le prototype (pour le JavaScript)
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
