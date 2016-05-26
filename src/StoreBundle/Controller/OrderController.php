<?php

namespace StoreBundle\Controller;

use CommonBundle\Controller\Controller;
use DeliveryBundle\Entity\NovaPoshtaDelivery;
use DeliveryBundle\Plugin\NovaPoshta\Model\Delivery;
use StoreBundle\Entity\Order;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="orders")
 */
class OrderController extends Controller
{
    /**
     * @Route(path="/orders/checkout", methods={"GET", "POST"}, name="orders_checkout")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function checkoutAction(Request $request)
    {
        $cart = $this->get('cart.manager')->get();

        if ($cart->getItems()->count() == 0) {
            $this->addFlash('warning', 'Вы не можете оформить пустой заказ');

            return $this->redirectToRoute('cart_show');
        }

        if (null == $user = $this->getUser()) {
            return $this->redirectToRoute('order_account');
        }

        $orderManager = $this->get('store.manager.order_manager');
        $order = $orderManager->get();
        $order->setUser($user);

        $form = $this->createForm('order', $order);

        return $this->render('StoreBundle:Order:checkout.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart,
        ]);
    }

    /**
     * @Route(path="/orders/{id}/success", methods={"GET"}, name="orders_success")
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function successAction(Request $request, $id)
    {
        $order = $this->get('doctrine.orm.entity_manager')->getRepository('StoreBundle:Order')->find($id);

        if (!$order) {
            throw new NotFoundHttpException();
        }

        return $this->render('StoreBundle:Order:show.html.twig', ['order' => $order]);
    }

    /**
     * @Route(name="order_account", path="/order/checkout/account", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function accountAction(Request $request)
    {
        $cart = $this->get('cart.manager')->get();

        return $this->render('StoreBundle:Order:account.html.twig', ['cart' => $cart]);
    }

    /**
     * @Route(name="order_confirm", path="/order/{id}/confirm", methods={"GET"})
     *
     * @param Request $request
     * @param int     $id
     *
     * @return RedirectResponse|Response
     */
    public function confirmAction(Request $request, $id)
    {
        if (!$user = $this->getUser()) {
            return $this->redirectToRoute('order_account');
        }

        $order = $this->get('doctrine.orm.entity_manager')
            ->getRepository('StoreBundle:Order')
            ->findOneBy([
                'id' => $id,
                'user' => $user,
                'status' => Order::STATUS_CREATED
            ]);

        if (!$order) {
            throw new NotFoundHttpException();
        }

        return $this->render('StoreBundle:Order:confirm.html.twig', [
            'order' => $order
        ]);
    }
}
