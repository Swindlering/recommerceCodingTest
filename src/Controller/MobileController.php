<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MobileController
{
    private $productRepository;

    public function __construct(
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/mobile/{id}", name="get_one_mobile", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        // TO DO move to Services directory 
        $mobile = $this->productRepository->findOneBy(['id' => $id])->toArray();
        $mobile['brand'] = $mobile['brand']->getName();
        $mobile['order'] = $mobile['order']->getId();

        return new JsonResponse($mobile, Response::HTTP_OK);
    }

    /**
     * @Route("/mobile", name="get_all_mobile", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        // TO DO move to Services directory 
        $mobiles = $this->productRepository->findAll();
        $data = [];

        foreach ($mobiles as $mobile) {
            $dataMobile = $mobile->toArray();
            $mobile['brand'] = $mobile['brand']->getName();
            $mobile['order'] = $mobile['order']->getId();

            $data[] = $dataMobile;
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
