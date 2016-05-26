<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 03.02.16
 * Time: 4:31
 */

namespace ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends Controller
{
    /**
     * @Route(path="/{alias}", name="dynamic_page", methods={"GET"})
     *
     * @param Request $request
     * @param         $alias
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, $alias)
    {
        $manager = $this->get('doctrine.orm.entity_manager');

        $page = $manager->getRepository('ContentBundle:Page')->findOneBy([
            'url' => $alias,
            'enabled' => true
        ]);

        if (!$page) {
            throw new NotFoundHttpException;
        }

        return $this->render('ContentBundle:Page:show.html.twig', ['page' => $page]);
    }
}