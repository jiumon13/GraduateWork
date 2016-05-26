<?php

namespace CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $doctrine = $this->get('doctrine');
        $categories = $doctrine->getRepository('CategoryBundle:Category')->findBy(['enabled' => true], []);
        $featuredProducts = $doctrine->getRepository('StoreBundle:Product')->findBy(['enabled' => true], ['popularity' => 'DESC'], 9);

        return $this->render('CommonBundle::home.html.twig',
            [
                'title' => 'Популярные товары',
                'categories' => $categories,
                'products' => $featuredProducts,
            ]
        );
    }
}