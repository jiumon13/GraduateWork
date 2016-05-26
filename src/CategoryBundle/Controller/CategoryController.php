<?php

namespace CategoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    const PER_PAGE = 9;

    public function listAction(Request $request)
    {
        $doctrine = $this->get('doctrine');

        $productRows = $doctrine->getRepository('StoreBundle:Product')->getCount();

        $page = $request->query->get('page', 1);
        $qtyPages = ceil($productRows/self::PER_PAGE);

        if ($page > $qtyPages) {
            $offset = null;
        } else {
            $offset = ($page * self::PER_PAGE) - self::PER_PAGE;
        }

        $categories = $doctrine->getRepository('CategoryBundle:Category')->findBy(['enabled' => true], []);
        $products = $doctrine->getRepository('StoreBundle:Product')->findBy(['enabled' => true], ['popularity' => 'DESC'], self::PER_PAGE, $offset);

        return $this->render('CategoryBundle::list.html.twig',
            [
                'title' => 'Каталог продуктов',
                'categories' => $categories,
                'products' => $products,
                'currentPage' => $page,
                'qtyPages' => $qtyPages
            ]
        );
    }

    public function showAction(Request $request, $alias)
    {
        $doctrine = $this->get('doctrine');

        $category = $doctrine->getRepository('CategoryBundle:Category')->findOneBy(['enabled' => true, 'alias' => $alias]);
        $productRows = $doctrine->getRepository('StoreBundle:Product')->getCount($category->getId());

        $page = $request->query->get('page', 1);
        $qtyPages = ceil($productRows/self::PER_PAGE);

        if ($page > $qtyPages) {
            $offset = null;
        } else {
            $offset = ($page * self::PER_PAGE) - self::PER_PAGE;
        }

        $categories = $doctrine->getRepository('CategoryBundle:Category')->findBy(['enabled' => true]);
        $products = $doctrine->getRepository('StoreBundle:Product')->findByOffset($category->getId(), self::PER_PAGE, $offset);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        return $this->render('CategoryBundle::list.html.twig',
            [
                'title' => $category->getName(),
                'categories' => $categories,
                'products' => $products,
                'currentPage' => $page,
                'qtyPages' => $qtyPages
            ]
        );
    }
}