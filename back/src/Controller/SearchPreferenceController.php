<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchPreferenceController extends AbstractController
{
    #[Route('/search/preference', name: 'app_search_preference')]
    public function index(): Response
    {
        return $this->render('search_preference/index.html.twig', [
            'controller_name' => 'SearchPreferenceController',
        ]);
    }
}
