<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 28.11.15
 * Time: 2:02
 */

namespace CartBundle\Controller\API;

use CartBundle\Exception\CartException;
use CommonBundle\Exception\ValidationException;
use Doctrine\Common\Collections\ArrayCollection;
use StoreBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class CartController extends Controller
{
    /**
     * @param ConstraintViolationListInterface $list
     *
     * @return array
     */
    protected function getErrors(ConstraintViolationListInterface $list)
    {
        $errors = [];
        for ($i = 0; $i < $list->count(); $i++) {
            $violation = $list->get($i);
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }

    /**
     * @param array $data
     *
     * @return Response
     *
     */
    protected function buildSuccessResponse(array $data)
    {
        $plain = json_encode([
            'success' => true,
            'data'    => $data,
            'errors'  => [],
        ]);

        return new Response($plain);
    }

    /**
     * @param $errors
     *
     * @return Response
     */
    protected function buildFailResponse($errors)
    {
        if (!is_array($errors)) {
            $errors = [$errors];
        }

        $plain = json_encode([
            'success' => false,
            'data'    => null,
            'errors'  => $errors,
        ]);

        return new Response($plain);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        try {
            $productId = (string) $request->request->get('product');
            $servicesIds = (array) $request->request->get('services');
            $quantity = (int) $request->request->get('quantity');

            $product = $this->getDoctrine()->getRepository('StoreBundle:Product')->findOneBy([
                'id'      => $productId,
                'enabled' => true,
            ]);

            if (!$product) {
                throw new CartException('Product not found');
            }

            if ($quantity <= 0) {
                throw new CartException('Bad quantity');
            }

            $services = new ArrayCollection();
            foreach ($servicesIds as $id) {
                if (!$product->getServices()->containsKey($id)) {
                    throw new CartException('Bad service');
                }
                $services->add($product->getServices()->get($id));
            }

            $this->get('cart.manager')->add($product, $services, $quantity);
            $response = $this->buildSuccessResponse([
                'id'       => $product->getId(),
                'name'     => $product->getName(),
                'alias'    => $product->getAlias(),
                'price'    => $product->getPrice(),
                'quantity' => $quantity,
            ]);
        } catch (ValidationException $e) {
            $errors = $this->getErrors($e->getViolations());
            $response = $this->buildFailResponse($errors);
        } catch (CartException $e) {
            $response = $this->buildFailResponse($e->getMessage());
        }

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $cart = $this->get('cart.manager')->get();
        $items = [];
        foreach ($cart->all() as $item) {
            $product = $item->getProduct();
            $quantity = $item->getQuantity();

            $services = [];
            foreach ($item->getServices() as $service) {
                $services[] = [
                    'name'     => $service->getName(),
                    'price'    => $service->getPrice(),
                    'currency' => $product->getCurrency()->getName(),
                ];
            }

            $items[] = [
                'id'       => $product->getId(),
                'name'     => $item->getName(true),
                'alias'    => $product->getAlias(),
                'price'    => $item->getPrice(true),
                'currency' => $product->getCurrency()->getName(),
                'services' => $services,
                'quantity' => $quantity,
            ];
        }

        return $this->buildSuccessResponse($items);
    }
}