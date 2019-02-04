<?php

namespace App\Controller\front;

use App\Form\ExplorerType;
use App\Services\ExplorerFilters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package App\Controller
 * @Route("/search", name="app_front_")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/", name="search_index", methods={"GET", "POST"})
     */
    public function index(Request $request, ExplorerFilters $explorerFilters)
    {
        $form = $this->createForm(ExplorerType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $results = $explorerFilters->filters($data);
        }

        return $this->render('front/search/index.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
            'results' => $results,
        ]);
    }
}
