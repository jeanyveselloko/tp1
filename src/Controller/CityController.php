<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType; // Assurez-vous d'avoir le bon formulaire
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/cities")
 */
class CityController extends AbstractController
{
    /**
     * @Route("/", name="api_city_index", methods={"GET"})
     */
    public function index(CityRepository $cityRepository): JsonResponse
    {
        $cities = $cityRepository->findAll();
        $data = [];

        foreach ($cities as $city) {
            $data[] = [
                'id' => $city->getId(),
                'name' => $city->getName(),
                'postCode' => $city->getPostCode(),
                // Ajoutez d'autres attributs que vous souhaitez inclure
                'announces' => $city->getAnnounces()->map(function ($announce) {
                    return $announce->getId();
                })->toArray(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="api_city_new", methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        // Récupérez les données POST du corps de la demande.
        $data = json_decode($request->getContent(), true);

        // Créez une nouvelle instance de City et attribuez les valeurs des données.
        $city = new City();
        $city->setName($data['name']);
        $city->setPostCode($data['postCode']);
        // Définissez d'autres attributs selon vos besoins.

        // Persistez la nouvelle instance de City dans la base de données.
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($city);
        $entityManager->flush();

        // Retournez une réponse JSON appropriée pour indiquer que la ville a été créée avec succès.
        return $this->json(['message' => 'City created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="api_city_show", methods={"GET"})
     */
    public function show(City $city): JsonResponse
    {
        // Créez un tableau de données pour représenter la ville.
        $data = [
            'id' => $city->getId(),
            'name' => $city->getName(),
            'postCode' => $city->getPostCode(),
            // Ajoutez d'autres attributs que vous souhaitez inclure
            'announces' => $city->getAnnounces()->map(function ($announce) {
                return $announce->getId();
            })->toArray(),
        ];

        // Retournez une réponse JSON avec les données de la ville.
        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/edit", name="api_city_edit", methods={"PUT"})
     */
    public function edit(Request $request, City $city): JsonResponse
    {
        // Récupérez les données POST du corps de la demande.
        $data = json_decode($request->getContent(), true);

        // Mettez à jour les attributs de la ville avec les nouvelles données.
        $city->setName($data['name']);
        $city->setPostCode($data['postCode']);
        // Mettez à jour d'autres attributs selon vos besoins.

        // Persistez la mise à jour de la ville dans la base de données.
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Retournez une réponse JSON appropriée pour indiquer que la ville a été mise à jour avec succès.
        return $this->json(['message' => 'City updated'], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_city_delete", methods={"DELETE"})
     */
    public function delete(City $city): JsonResponse
    {
        // Supprimez la ville de la base de données.
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($city);
        $entityManager->flush();

        // Retournez une réponse JSON appropriée pour indiquer que la ville a été supprimée avec succès.
        return $this->json(['message' => 'City deleted'], Response::HTTP_NO_CONTENT);
    }
}