<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController
{
    private $orderRepository;
    private $productRepository;

    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/order/{id}", name="get_one_order", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        // TO DO move to Services directory 
        $order = $this->orderRepository->findOneBy(['id' => $id])->toArray();
        $mobiles = $order['mobiles']->getValues();

        $order['mobilesIds'] = array_map(function ($mobile) {
            return $mobile->getId();
        }, $mobiles);

        unset($order['mobiles']);

        return new JsonResponse($order, Response::HTTP_OK);
    }

    /**
     * @Route("/order", name="get_all_order", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        // TO DO move to Services directory 
        $orders = $this->orderRepository->findAll();
        $data = [];

        foreach ($orders as $order) {
            $dataOrder = $order->toArray();
            $mobiles = $dataOrder['mobiles']->getValues();

            $dataOrder['mobilesIds'] = array_map(function ($mobile) {
                return $mobile->getId();
            }, $mobiles);

            unset($dataOrder['mobiles']);

            $data[] = $dataOrder;
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/order/", name="add_order", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $mobilesIds =  $data['mobilesIds'];
        $customerEmail = $data['customerEmail'];

        // TO DO move Check and Insert in Services directory 
        if (empty($mobilesIds) || empty($customerEmail) || !is_array($mobilesIds) || count($mobilesIds) < 1) {
            throw new NotFoundHttpException('Missing or wrong mandatory parameters!');
        }

        // The "customerEmail" field must be formatted as an email address 
        if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            echo "Email address '$customerEmail' is considered invalid.\n";
        }

        // manage Order amount 
        // TO DO Check for wrong Ids and give feeds back
        $mobiles =  $this->productRepository->findBy(array('id' => $mobilesIds));
        $amount = 0;
        foreach ($mobiles as $mobile) {
            $amount += $mobile->getPrice();
        }

        $this->orderRepository->saveOrder($mobiles, $customerEmail, $amount);
        return new JsonResponse(['status' => 'Order created!'], Response::HTTP_CREATED);
    }
}
