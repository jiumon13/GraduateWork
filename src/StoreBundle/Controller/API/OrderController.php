<?php

namespace StoreBundle\Controller\API;

use CommonBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use StoreBundle\Entity\Order;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 *
 * @package StoreBundle\Controller\API
 */
class OrderController extends Controller
{
    /**
     * @Route(path="/api/v1/order/checkout", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function checkoutAction(Request $request)
    {
        if (null == $user = $this->getUser()) {
            return new JsonResponse([
                'success'  => false,
                'data'     => [],
                'redirect' => $this->generateUrl('order_account'),
            ], 403);
        }

        $order = $this->get('store.manager.order_manager')->get();
        $order->setUser($user);

        $form = $this->createForm('order', $order);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('store.manager.order_manager')->clear();
            $this->get('cart.manager')->clear();

            $this->get('doctrine.orm.entity_manager')->persist($order);
            $this->get('doctrine.orm.entity_manager')->flush();

            return new JsonResponse([
                'success'  => true,
                'data'     => ['order' => $order->getId()],
                'redirect' => $this->generateUrl('order_confirm', ['id' => $order->getId()]),
            ]);
        } else {
            return new JsonResponse(['success' => false, 'errors' => $this->getFormErrors($form)]);
        }
    }

    /**
     * @Route(name="api_order_confirm", path="/api/v1/order/confirm", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function confirmAction(Request $request)
    {
        if (!$user = $this->getUser()) {
            return new JsonResponse([
                'status' => false,
                'data' => [],
                'redirect' => $this->generateUrl('order_account')
            ], 403);
        }

        $order = $request->request->get('order');

        $order = $this->get('doctrine.orm.entity_manager')
            ->getRepository('StoreBundle:Order')
            ->findOneBy([
                'id'     => isset($order['id']) ? $order['id'] : null,
                'user'   => $user,
                'status' => Order::STATUS_CREATED,
            ]);

        if (!$order) {
            return new JsonResponse([
                'success' => false,
                'data' => []
            ], 404);
        }

        $order->setStatus(Order::STATUS_NEW);

        $items = $order->getCart()->getItems();

        foreach($items as $item) {
            $popularity = $item->getProduct()->getPopularity();
            $item->getProduct()->setPopularity($popularity + 1);
            $this->get('doctrine.orm.entity_manager')->persist($item);
        }
        
        $this->get('doctrine.orm.entity_manager')->persist($order);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse([
            'success' => true,
            'data' => [],
            'redirect' => $this->generateUrl('orders_success', ['id' => $order->getId()])
        ]);
    }
}