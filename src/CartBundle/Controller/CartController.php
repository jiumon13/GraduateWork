<?php

namespace CartBundle\Controller;

use CartBundle\Entity\Cart;
use CartBundle\Form\Type\CartType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function showAction(Request $request)
    {
        $doctrine = $this->get('doctrine');

        $categories = $doctrine->getRepository('CategoryBundle:Category')->findBy(['enabled' => true]);

        $cart = $this->get('cart.manager')->get();
        $form = $this->createForm(new CartType(), $cart);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Cart $cart */
            $cart = $form->getData();

            // Save cart
            $this->get('doctrine.orm.entity_manager')->persist($cart);
            $this->get('doctrine.orm.entity_manager')->flush();

            if ($form->get('save')->isClicked()) {
                $response = $this->redirectToRoute('cart_show');
            } else {
                $response = $this->redirectToRoute('orders_checkout');
            }
        } else {
            $response = $this->render('CartBundle::cart.html.twig', [
                'form' => $form->createView(),
                'categories' => $categories,
            ]);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function checkoutAction(Request $request)
    {
        $cart = $this->get('cart.manager')->get();
        $form = $this->createForm(new CartType(), $cart, ['disabled' => true]);
        $form->handleRequest($request);
        if ($form->isValid()) {

        } else {
            $response = $this->render('CartBundle::checkout.html.twig', [
                'form' => $form->createView()
            ]);
        }

        return $response;
    }
}
