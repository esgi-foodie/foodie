<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Recipe;
use App\Entity\RecipeStep;
use App\Entity\Ingredient;
use App\Entity\User;
use Faker;
use Symfony\Component\HttpFoundation\File\File;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Placeholder($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Food($faker));

        //RECIPE
        for ($i = 0; $i < 100; $i++) {
            $recipe = new Recipe();
            $recipe->setTitle($faker->foodName());

            //INGREDIENTS
            for ($y = 0; $y < rand(3,10); $y++){
                $measuringUnit =  ['g','kg','piece','mL','cL','L'];
                $keyMeasuringUnit = array_rand($measuringUnit);
                $arrIngr=[];
                $ingredient = new Ingredient();
                $ingredient->setName($faker->ingredient);
                $ingredient->setQuantity(rand(1,50));
                $ingredient->setProtein(rand(1,10));
                $ingredient->setCarbohydrate(rand(1,10));
                $ingredient->setFat(rand(1,10));
                $ingredient->setMeasuringUnit($measuringUnit[$keyMeasuringUnit]);
                $ingredient->setRecipe($recipe);
                $arrIngr[] = $ingredient;

                $manager->persist($ingredient);
            }
            $recipe->getIngredients($arrIngr);

            //RECIPE_STEPS
            for ($y = 0; $y < rand(3,10); $y++){
                $arrStep=[];
                $recipeStep = new RecipeStep();
                $recipeStep->setTitle($faker->sentence( 5,  true));
                $recipeStep->setStepNumber($y);
                $recipeStep->setContent($faker->paragraph( 4,  true));
                $recipeStep->setRecipe($recipe);
                //$recipeStep->addImage($faker->placeholder());
                array_push($arrStep,$recipeStep);

                $manager->persist($recipeStep);
            }

            $date = date_create_from_format('H:i:s',$faker->time());

            $recipe->setTime($date);
            $category = $manager->getRepository(Category::class)->find(rand(1,20));
            $recipe->addCategory($category);
            $recipe->getRecipeSteps($arrStep);
            $recipe->getComments([]);
            $recipe->setImageFile(new File('assets/images/loclac.jpg'));
            $recipe->setImageName('food');
            $recipe->getRecipeFavorite([]);

            //RANDOM USER SELECTED
            $user = $manager->getRepository(User::class)->find(rand(1,20));
            $recipe->setUserRecipe($user);

            $manager->persist($recipe);
            $manager->flush();
        }
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on user
     *
     * @return array
     */
    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
