<?php

namespace App\Controller;

use App\Entity\AccomodationType;
use App\Repository\AccomodationTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/accomodation-types")
 */

class AccomodationTypeController extends AbstractController
{
   /**
     * @Route("/", name="api_accomodation_type_index", methods={"GET"})
     */
    public function index(AccomodationTypeRepository $repository): JsonResponse
    {
        $accomodationTypes = $repository->findAll();
        $data = [];
        foreach ($accomodationTypes as $accomodationType) {
            $data[] = [
                'id' => $accomodationType->getId(),
                'name' => $accomodationType->getName(),
            ];
        }
        return $this->json($data, 200);
    }

    /**
     * @Route("/", name="api_accomodation_type_new", methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $accomodationType = new AccomodationType();
        $accomodationType->setName($data['name']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($accomodationType);
        $entityManager->flush();

        return $this->json(['message' => 'AccomodationType created'], 201);
    }

    /**
     * @Route("/{id}", name="api_accomodation_type_show", methods={"GET"})
     */
    public function show(AccomodationType $accomodationType): JsonResponse
    {
        $data = [
            'id' => $accomodationType->getId(),
            'name' => $accomodationType->getName(),
        ];
        return $this->json($data, 200);
    }

    /**
     * @Route("/{id}", name="api_accomodation_type_update", methods={"PUT"})
     */
    public function update(Request $request, AccomodationType $accomodationType): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $accomodationType->setName($data['name']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->json(['message' => 'AccomodationType updated'], 200);
    }

    /**
     * @Route("/{id}", name="api_accomodation_type_delete", methods={"DELETE"})
     */
    public function delete(AccomodationType $accomodationType): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($accomodationType);
        $entityManager->flush();

        return $this->json(['message' => 'AccomodationType deleted'], 204);
    }
}