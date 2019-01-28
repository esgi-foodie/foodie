<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\RecipeStep;
use App\Form\Type\RecipeStepType;
use App\Form\Type\IngredientType;
use Doctrine\DBAL\Types\TimeType;
use Gedmo\Mapping\Driver\File;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType as TimeTypeField;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,['label' => 'Titre'])
            ->add('protein',IntegerType::class,['label' => 'Protéine'])
            ->add('carbohydrate',IntegerType::class,['label' => 'Glucides'])
            ->add('fat',IntegerType::class,['label' => 'Lipides'])
            ->add('pathCoverImg',FileType::class,['label' => 'image','data_class' => null])
            ->add('time',TimeTypeField::class,['label' => 'Temps'])
            ->add('categories', EntityType::class, [
                'label'        => 'Categories',
                'class'        => Category::class,
                'choice_label' => 'name',
                'multiple'     => true,
                'required'     => false,
            ])
            ->add('ingredients', CollectionType::class, [
                'entry_type' => IngredientType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'Ingredients',
            ])
            ->add('recipeSteps', CollectionType::class, [
                'entry_type' => RecipeStepType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'Etapes',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
