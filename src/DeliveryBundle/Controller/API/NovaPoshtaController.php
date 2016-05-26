<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 25.02.16
 * Time: 12:29
 */

namespace DeliveryBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NovaPoshtaController extends Controller
{
    /**
     * @Route(path="/api/v1/nova_poshta/cities", methods={"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function citiesListAction(Request $request)
    {
        $name = (string) $request->query->get('name');

        $cities = $this->getDoctrine()
            ->getManager()
            ->getRepository('DeliveryBundle:City')
            ->search($name);

        $data = [];
        foreach ($cities as $city) {
            $data[] = [
                'id' => $city->getId(),
                'name' => $city->getName()
            ];
        }

        return new JsonResponse(['success' => true, 'data' => $data]);
    }

    /**
     * @Route(path="/api/v1/nova_poshta/warehouses", methods={"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function warehousesListAction(Request $request)
    {
        $city = (int) $request->query->get('city');

        $warehouses = $this->get('doctrine.orm.entity_manager')
            ->getRepository('DeliveryBundle:Warehouse')
            ->search($city);

        $data = [];
        foreach ($warehouses as $warehouse) {
            $data[] = [
                'id' => $warehouse->getId(),
                'number' => $warehouse->getNumber(),
                'address' => $warehouse->getAddress()
            ];
        }

        return new JsonResponse(['success' => true, 'data' => $data]);
    }
}