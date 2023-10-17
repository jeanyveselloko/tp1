<?php

namespace App\Controller;

use App\Entity\Facilities;
use App\Form\FacilitiesType;
use App\Repository\FacilitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/facilities")
 */

class FacilitiesController extends AbstractController
{/**
     * @Route("/", name="api_facilities_index", methods={"GET"})
     */
    public function index(FacilitiesRepository $facilitiesRepository): JsonResponse
    {
        $facilities = $facilitiesRepository->findAll();
        $data = [];

        foreach ($facilities as $facility) {
            $data[] = [
                'id' => $facility->getId(),
                'name' => $facility->getName(),
                'announces' => $facility->getAnnounces()->map(function ($announce) {
                    return $announce->getId();
                })->toArray(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="api_facilities_new", methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $facility = new Facilities();
        $facility->setName($data['name']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($facility);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Facility created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="api_facilities_show", methods={"GET"})
     */
    public function show(Facilities $facility): JsonResponse
    {
        $data = [
            'id' => $facility->getId(),
            'name' => $facility->getName(),
            'announces' => $facility->getAnnounces()->map(function ($announce) {
                return $announce->getId();
            })->toArray(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/edit", name="api_facilities_edit", methods={"PUT"})
     */
    public function edit(Request $request, Facilities $facility): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $facility->setName($data['name']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new JsonResponse(['message' => 'Facility updated'], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_facilities_delete", methods={"DELETE"})
     */
    public function delete(Facilities $facility): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($facility);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Facility deleted'], Response::HTTP_NO_CONTENT);
    }
}
