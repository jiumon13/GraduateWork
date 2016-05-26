<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 01.09.15
 * Time: 13:36
 */

namespace StoreBundle\Controller;

use StoreBundle\Form\Type\FilterFormType;
use StoreBundle\Model\Filter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @param         $alias
     *
     * @return Response
     */
    public function showAction(Request $request, $alias)
    {
        $doctrine = $this->get('doctrine');

        $categories = $doctrine->getRepository('CategoryBundle:Category')->findBy(['enabled' => true]);
        $product = $this->getDoctrine()->getRepository('StoreBundle:Product')->findOneBy([
            'alias'   => $alias,
            'enabled' => true,
        ]);

        if (!$product) {
            throw new NotFoundHttpException();
        }

        return $this->render('StoreBundle:Product:show.html.twig', ['product' => $product, 'categories' => $categories]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $filter = new Filter();

//        if ($request->query->has('category')) {
//            $category = $this->getDoctrine()->getRepository('CategoryBundle:Category')->findOneBy([
//                'alias'   => $request->query->get('category'),
//                'enabled' => true,
//            ]);
//            if ($category) {
//                $filter->setCategory($category);
//            }
//        }

        $filterForm = $this->createForm(new FilterFormType(), $filter);
        $filterForm->handleRequest($request);
        if ($filterForm->isValid()) {
            $filter = $filterForm->getData();
        }

        $products = $this->getDoctrine()->getRepository('StoreBundle:Product')->findByFilter($filter);

        return $this->render('StoreBundle:Product:list.html.twig', [
            'title'       => 'Поиск',
            'description' => '',
            'products'    => $products,
            'filter'      => $filterForm->createView(),
        ]);
    }

    /**
     * @Route(path="/categories/{category}/products", name="category_products_list", methods={"GET"})
     *
     * @param Request $request
     * @param string  $category
     *
     * @return Response
     */
    public function listInCategoryAction(Request $request, $category)
    {
        $filter = new Filter();

        $category = $this->getDoctrine()->getRepository('CategoryBundle:Category')->findOneBy([
            'alias'   => $category,
            'enabled' => true,
        ]);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        $filterForm = $this->createForm(new FilterFormType(), $filter);
        $filterForm->handleRequest($request);
        if ($filterForm->isValid()) {
            $filter = $filterForm->getData();
        }

        $products = $this->getDoctrine()->getRepository('StoreBundle:Product')->findByFilter($filter);

        return $this->render('StoreBundle:Product:list_category.html.twig', [
            'title'       => $category->getName(),
            'description' => '',
            'category'    => $category,
            'products'    => $products,
            'filter'      => $filterForm->createView(),
        ]);
    }
}