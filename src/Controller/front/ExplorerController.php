<?php

namespace App\Controller\front;

use App\Form\ExplorerType;
use App\Repository\UserRepository;
use App\Services\ExplorerFilters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RecipeRepository;

/**
 * Class ExplorerController
 * @package App\Controller
 * @Route("/explorer", name="app_front_")
 */
class ExplorerController extends AbstractController
{
    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @param RecipeRepository $recipeRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="explorer_index", methods={"GET", "POST"})
     */
    public function index(Request $request, UserRepository $userRepository, RecipeRepository $recipeRepository)
    {
        $form = $this->createForm(ExplorerType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $categoriesId = [];

            if (!$data['category']->isEmpty()) {
                foreach ($data['category'] as $category) {
                    $categoriesId[] = $category->getId();
                }
            }

            $users = $data['query'] ? $userRepository->findByUsername($data['query']) : null;
            $recipes = $this->isAllQueryNotNull($data) ? $recipeRepository->findWithFilters($data, $categoriesId) : null;
            $results = ['users' => $users, 'recipes' => $recipes];
        } else {
            $data = [];
            $results['recipes'] = $recipeRepository->findByUserSuggestion($this->getUser()->getId(),'20');
        }

        return $this->render('front/explorer/index.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
            'results' => $results,
        ]);
    }

    /**
     * @param $data
     * @return bool
     */
    public function isAllQueryNotNull($data)
    {
        $queries = [];

        foreach ($data as $filter) {
            if (gettype($filter) === 'object') {
                if (!$filter->isEmpty()) {
                    $queries[] = 1;
                }
            } else if ($filter) {
                $queries[] = 1;
            } else {
                $queries[] = 0;
            }
        }

        return in_array(1, $queries);
    }
}
