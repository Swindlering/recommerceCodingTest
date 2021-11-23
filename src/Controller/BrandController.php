<?php

namespace App\Controller;

use App\Repository\BrandRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BrandController
{
    private $brandRepository;

    public function __construct(
        BrandRepository $brandRepository
    ) {
        $this->brandRepository = $brandRepository;
    }

    /**
     * @Route("/brand/{id}", name="get_one_brand", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        return new JsonResponse(
            $this->brandRepository->findOneBy(['id' => $id])->toArray(),
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/mobile", name="get_all_mobile", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        // TO DO move to Services directory 
        $brands = $this->brandRepository->findAll();
        $data = [];

        foreach ($brands as $brand) {
            $data[] = $brand->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
