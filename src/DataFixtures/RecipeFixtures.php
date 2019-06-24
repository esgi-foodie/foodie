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
            $protein = rand(1, 15);
            $carbohydrate = rand(1, 15);
            $fat = rand(1, 15);
            $nbIngredients = rand(3, 6);

            //INGREDIENTS
            for ($y = 0; $y < $nbIngredients; $y++){
                $measuringUnit =  ['g','kg','piece','mL','cL','L'];
                $keyMeasuringUnit = array_rand($measuringUnit);
                $ingredient = new Ingredient();
                $ingredient->setName($faker->ingredient);
                $ingredient->setQuantity(rand(1,50));
                $ingredient->setProtein($protein);
                $ingredient->setCarbohydrate($carbohydrate);
                $ingredient->setFat($fat);
                $ingredient->setMeasuringUnit($measuringUnit[$keyMeasuringUnit]);
                $ingredient->setRecipe($recipe);
                $recipe->addIngredient($ingredient);


                $manager->persist($ingredient);
            }

            //RECIPE_STEPS
            for ($y = 0; $y < rand(3,10); $y++){
                $arrStep=[];
                $recipeStep = new RecipeStep();
                $recipeStep->setTitle($faker->sentence( 5,  true));
                $recipeStep->setStepNumber($y);
                $recipeStep->setContent($faker->paragraph( 4,  true));
                $recipeStep->setRecipe($recipe);
                $arrStep[] = $recipeStep ;

                $manager->persist($recipeStep);
            }

            $date = date_create_from_format('H:i:s',$faker->time());

            $recipe->setTime($date);
            $category = $manager->getRepository(Category::class)->find(rand(1,20));
            $recipe->addCategory($category);
            $recipe->setCalory(($nbIngredients * $protein * 4) + ($nbIngredients * $carbohydrate * 4) + ($nbIngredients * $fat * 9));
            $recipe->setProtein($nbIngredients * $protein);
            $recipe->setCarbohydrate($nbIngredients * $carbohydrate);
            $recipe->setFat($nbIngredients * $fat);
            $recipe->getComments([]);
            $recipe->getRecipeSteps($arrStep);
            $recipe->setImageName('');
            $recipe->getRecipeFavorite([]);

            //RANDOM USER SELECTED
            $user = $manager->getRepository(User::class)->find(rand(1,31));
            $recipe->setUserRecipe($user);

            $manager->persist($recipe);
        }

        foreach ($this->getMockedRecipe() as $recipeMock) {
            $recipe = new Recipe();
            $recipe->setTitle($recipeMock['title']);

            $date = date_create_from_format('H:i:s',$faker->time());

            $recipe->setTime($date);
            $category = $manager->getRepository(Category::class)->findOneBy(['name' => $recipeMock['category']]);

            $category->setName($recipeMock['category']);
            $recipe->addCategory($category);
            $recipe->setCalory($recipeMock['calory']);
            $recipe->setProtein($recipeMock['protein']);
            $recipe->setCarbohydrate($recipeMock['carbohydrate']);
            $recipe->setFat($recipeMock['fat']);
            $recipe->setImageName('');

            foreach ($recipeMock['ingredients'] as $ingredientMock){
                $ingredient = new Ingredient();
                $ingredient->setName($ingredientMock['title']);
                $ingredient->setQuantity($ingredientMock['quantity']);
                $ingredient->setProtein($ingredientMock['protein']);
                $ingredient->setCarbohydrate($ingredientMock['carbohydrate']);
                $ingredient->setFat($ingredientMock['fat']);
                $ingredient->setMeasuringUnit($ingredientMock['measuringUnit']);
                $ingredient->setRecipe($recipe);
                $recipe->addIngredient($ingredient);
                $manager->persist($ingredient);
            }

            //RECIPE_STEPS
            foreach ($recipeMock['recipeSteps'] as $recipeStepMock) {
                $recipeStep = new RecipeStep();
                $recipeStep->setTitle($recipeStepMock['title']);
                $recipeStep->setStepNumber($recipeStepMock['stepNumber']);
                $recipeStep->setContent($recipeStepMock['content']);
                $recipeStep->setRecipe($recipe);
                $recipe->addRecipeStep($recipeStep);
                $manager->persist($recipeStep);
            }

            //RANDOM USER SELECTED
            $user = $manager->getRepository(User::class)->findOneBy(['email' => 'chloe@gmail.com']);
            $recipe->setUserRecipe($user);


        }
        $manager->flush();

    }

    private function getMockedRecipe(){
        return [
            [
                'title' => 'GLACE PROTÉINÉE À LA FRAISE',
                'category' => 'Protéiné',
                'calory' => 96,
                'protein' => 10,
                'carbohydrate' => 4,
                'fat' => 4,
                'ingredients' => [
                    [
                        'title' => 'Lait demi-écrémé',
                        'quantity' => 70,
                        'protein' => 7,
                        'carbohydrate' => 10,
                        'fat' => 4,
                        'measuringUnit' => 'mL',
                    ],
                    [
                        'title' => 'Yaourt grecque',
                        'quantity' => 150,
                        'protein' => 12,
                        'carbohydrate' => 17,
                        'fat' => 5,
                        'measuringUnit' => 'g',
                    ],
                ],
                'recipeSteps' =>
                    [
                        [
                            'title'  => '',
                            'stepNumber' => '1',
                            'content' => 'Dans un mixeur, placer tous les ingrédients à l\'exception des fruits rouges et mixer jusqu\'à obtention d\'une préparation crémeuse. '
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '2',
                            'content' => 'Mettre les fruits rouges dans un moule de votre choix puis verser la préparation par-dessus.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '3',
                            'content' => 'Conseil : vous avez le batonnet foodspring chez vous ? Super ! Il est parfait pour cette recette.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '4',
                            'content' => 'Placer minimum 8 heures au congélateur puis retirer du moule.'
                        ],
                    ]
            ],
        ];
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
