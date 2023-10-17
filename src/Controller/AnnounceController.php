<?php

namespace App\Controller;

use App\Entity\Announce;
use App\Repository\AnnounceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/announces")
 */
class AnnounceController extends AbstractController
{
    /**
     * @Route("/", name="api_announce_index", methods={"GET"})
     */
    public function index(AnnounceRepository $repository): JsonResponse
    {
        $announces = $repository->findAll();
        $data = [];
        foreach ($announces as $announce) {
            $data[] = [
                'id' => $announce->getId(),
                'name' => $announce->getName(),
                'description' => $announce->getDescription(),
                'bedroomNumber' => $announce->getBedroomNumber(),
                'priceByNight' => $announce->getPriceByNight(),
                'disponibility' => $announce->getDisponibility()->format('Y-m-d'),
                'address' => $announce->getAddress(),
                'user' => $announce->getUser() ? $announce->getUser()->getId() : null,
                'city' => $announce->getCity() ? $announce->getCity()->getId() : null,
                'accomodationType' => $announce->getAccomodationType() ? $announce->getAccomodationType()->getId() : null,
                'announceType' => $announce->getAnnounceType() ? $announce->getAnnounceType()->getId() : null,
                'facilities' => $this->getFacilitiesData($announce->getFacilities()),
                'photos' => $this->getPhotosData($announce->getPhotos()),
                'bookings' => $this->getBookingsData($announce->getBookings()),
                // Ajoutez d'autres attributs que vous souhaitez inclure
            ];
        }
        return $this->json($data, 200);
    }

   /**
 * @Route("/", name="api_announce_new", methods={"POST"})
 */
public function new(Request $request): JsonResponse
{
    // Récupérez les données POST du corps de la demande.
    $data = json_decode($request->getContent(), true);

    // Créez une nouvelle instance de l'entité Announce.
    $announce = new Announce();

    // Remplissez l'annonce avec les données POST.
    $announce->setName($data['name']);
    $announce->setDescription($data['description']);
    $announce->setBedroomNumber($data['bedroomNumber']);
    $announce->setPriceByNight($data['priceByNight']);
    $announce->setDisponibility(new \DateTime($data['disponibility']));
    $announce->setAddress($data['address']);

    // Gérez les relations avec d'autres entités.

    // Relation avec l'utilisateur (User).
    $userId = $data['user_id'];
    $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
    if ($user) {
        $announce->setUser($user);
    }

    // Relation avec la ville (City).
    $cityId = $data['city_id'];
    $city = $this->getDoctrine()->getRepository(City::class)->find($cityId);
    if ($city) {
        $announce->setCity($city);
    }

    // Relation avec le type d'hébergement (AccomodationType).
    $accomodationTypeId = $data['accomodation_type_id'];
    $accomodationType = $this->getDoctrine()->getRepository(AccomodationType::class)->find($accomodationTypeId);
    if ($accomodationType) {
        $announce->setAccomodationType($accomodationType);
    }

    // Relation avec le type d'annonce (AnnounceType).
    $announceTypeId = $data['announce_type_id'];
    $announceType = $this->getDoctrine()->getRepository(AnnounceType::class)->find($announceTypeId);
    if ($announceType) {
        $announce->setAnnounceType($announceType);
    }

    // Relations ManyToMany (Facilities).
    $facilitiesIds = $data['facilities_ids'];
    $facilities = [];
    foreach ($facilitiesIds as $facilityId) {
        $facility = $this->getDoctrine()->getRepository(Facilities::class)->find($facilityId);
        if ($facility) {
            $facilities[] = $facility;
        }
    }
    $announce->setFacilities($facilities);

    // Autres relations...

    // Persistez la nouvelle annonce dans la base de données.
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($announce);
    $entityManager->flush();

    // Retournez une réponse JSON appropriée pour indiquer que l'annonce a été créée avec succès.
    return $this->json(['message' => 'Announce created'], 201);
}


    /**
     * @Route("/{id}", name="api_announce_show", methods={"GET"})
     */
    public function show(Announce $announce): JsonResponse
    {
        $data = [
            'id' => $announce->getId(),
            'name' => $announce->getName(),
            'description' => $announce->getDescription(),
            'bedroomNumber' => $announce->getBedroomNumber(),
            'priceByNight' => $announce->getPriceByNight(),
            'disponibility' => $announce->getDisponibility()->format('Y-m-d'),
            'address' => $announce->getAddress(),
            'user' => $announce->getUser() ? $announce->getUser()->getId() : null,
            'city' => $announce->getCity() ? $announce->getCity()->getId() : null,
            'accomodationType' => $announce->getAccomodationType() ? $announce->getAccomodationType()->getId() : null,
            'announceType' => $announce->getAnnounceType() ? $announce->getAnnounceType()->getId() : null,
            'facilities' => $this->getFacilitiesData($announce->getFacilities()),
            'photos' => $this->getPhotosData($announce->getPhotos()),
            'bookings' => $this->getBookingsData($announce->getBookings()),
            // Ajoutez d'autres attributs que vous souhaitez inclure
        ];

        return $this->json($data, 200);
    }

   /**
 * @Route("/{id}", name="api_announce_update", methods={"PUT"})
 */
public function update(Request $request, Announce $announce): JsonResponse
{
    // Récupérez les données POST du corps de la demande.
    $data = json_decode($request->getContent(), true);

    // Mettez à jour les attributs de l'annonce avec les nouvelles données.
    if (isset($data['name'])) {
        $announce->setName($data['name']);
    }
    if (isset($data['description'])) {
        $announce->setDescription($data['description']);
    }
    // Mettez à jour d'autres attributs selon vos besoins.

    // Gérez les relations avec d'autres entités.

    // Relation avec l'utilisateur (User).
    if (isset($data['user_id'])) {
        $userId = $data['user_id'];
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        if ($user) {
            $announce->setUser($user);
        }
    }

    // Relation avec la ville (City).
    if (isset($data['city_id'])) {
        $cityId = $data['city_id'];
        $city = $this->getDoctrine()->getRepository(City::class)->find($cityId);
        if ($city) {
            $announce->setCity($city);
        }
    }

    // Relation avec le type d'hébergement (AccomodationType).
    if (isset($data['accomodation_type_id'])) {
        $accomodationTypeId = $data['accomodation_type_id'];
        $accomodationType = $this->getDoctrine()->getRepository(AccomodationType::class)->find($accomodationTypeId);
        if ($accomodationType) {
            $announce->setAccomodationType($accomodationType);
        }
    }

    // Relation avec le type d'annonce (AnnounceType).
    if (isset($data['announce_type_id'])) {
        $announceTypeId = $data['announce_type_id'];
        $announceType = $this->getDoctrine()->getRepository(AnnounceType::class)->find($announceTypeId);
        if ($announceType) {
            $announce->setAnnounceType($announceType);
        }
    }

    // Relations ManyToMany (Facilities).
    if (isset($data['facilities_ids'])) {
        $facilitiesIds = $data['facilities_ids'];
        $facilities = [];
        foreach ($facilitiesIds as $facilityId) {
            $facility = $this->getDoctrine()->getRepository(Facilities::class)->find($facilityId);
            if ($facility) {
                $facilities[] = $facility;
            }
        }
        $announce->setFacilities($facilities);
    }

    // Autres relations...

    // Persistez la mise à jour de l'annonce dans la base de données.
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->flush();

    // Retournez une réponse JSON appropriée pour indiquer que l'annonce a été mise à jour avec succès.
    return $this->json(['message' => 'Announce updated'], 200);
}

/**
 * @Route("/{id}", name="api_announce_delete", methods={"DELETE"})
 */
public function delete(Announce $announce): JsonResponse
{
    // Supprimez l'annonce de la base de données.
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($announce);
    $entityManager->flush();

    // Retournez une réponse JSON appropriée pour indiquer que l'annonce a été supprimée avec succès.
    return $this->json(['message' => 'Announce deleted'], 204);
}


    // Méthode pour récupérer des données de facilités
    private function getFacilitiesData($facilities)
    {
        $data = [];
        foreach ($facilities as $facility) {
            $data[] = [
                'id' => $facility->getId(),
                'name' => $facility->getName(),
                // Ajoutez d'autres attributs que vous souhaitez inclure
            ];
        }
        return $data;
    }

    // Méthode pour récupérer des données de photos
    private function getPhotosData($photos)
    {
        $data = [];
        foreach ($photos as $photo) {
            $data[] = [
                'id' => $photo->getId(),
                'url' => $photo->getUrl(),
                // Ajoutez d'autres attributs que vous souhaitez inclure
            ];
        }
        return $data;
    }

    // Méthode pour récupérer des données de réservations
    private function getBookingsData($bookings)
    {
        $data = [];
        foreach ($bookings as $booking) {
            $data[] = [
                'id' => $booking->getId(),
                'date' => $booking->getDate()->format('Y-m-d'),
                // Ajoutez d'autres attributs que vous souhaitez inclure
            ];
        }
        return $data;
    }
}