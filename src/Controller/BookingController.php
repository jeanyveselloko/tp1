<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType; // Assurez-vous d'avoir le bon formulaire
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/bookings")
 */

class BookingController extends AbstractController
{
     /**
     * @Route("/", name="api_booking_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository): JsonResponse
    {
        $bookings = $bookingRepository->findAll();
        $data = [];

        foreach ($bookings as $booking) {
            $data[] = [
                'id' => $booking->getId(),
                'bookingDate' => $booking->getBookingDate()->format('Y-m-d H:i:s'),
                'checkInDate' => $booking->getCheckInDate()->format('Y-m-d H:i:s'),
                'checkOutDate' => $booking->getCheckOutDate()->format('Y-m-d H:i:s'),
                'numberOfNight' => $booking->getNumberOfNight(),
                'TotalBooking' => $booking->getTotalBooking(),
                'priceByNight' => $booking->getPriceByNight(),
                'status' => $booking->getStatus(),
                // Ajoutez d'autres attributs que vous souhaitez inclure
                'user' => $booking->getUser() ? $booking->getUser()->getId() : null,
                'announce' => $booking->getAnnounce() ? $booking->getAnnounce()->getId() : null,
                'ratings' => $booking->getRatings()->map(function ($rating) {
                    return $rating->getId();
                })->toArray(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="api_booking_new", methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        // Récupérez les données POST du corps de la demande.
        $data = json_decode($request->getContent(), true);

        // Créez une nouvelle instance de Booking et attribuez les valeurs des données.
        $booking = new Booking();
        $booking->setBookingDate(new \DateTime($data['bookingDate']));
        $booking->setCheckInDate(new \DateTime($data['checkInDate']));
        $booking->setCheckOutDate(new \DateTime($data['checkOutDate']));
        $booking->setNumberOfNight($data['numberOfNight']);
        $booking->setTotalBooking($data['TotalBooking']);
        $booking->setPriceByNight($data['priceByNight']);
        $booking->setStatus($data['status']);
        // Définissez d'autres attributs selon vos besoins.

        // Associez l'utilisateur et l'annonce aux réservations (relations ManyToOne).
        $user = $this->getDoctrine()->getRepository(User::class)->find($data['user']);
        $announce = $this->getDoctrine()->getRepository(Announce::class)->find($data['announce']);
        $booking->setUser($user);
        $booking->setAnnounce($announce);

        // Persistez la nouvelle instance de Booking dans la base de données.
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($booking);
        $entityManager->flush();

        // Retournez une réponse JSON appropriée pour indiquer que la réservation a été créée avec succès.
        return $this->json(['message' => 'Booking created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="api_booking_show", methods={"GET"})
     */
    public function show(Booking $booking): JsonResponse
    {
        // Créez un tableau de données pour représenter la réservation.
        $data = [
            'id' => $booking->getId(),
            'bookingDate' => $booking->getBookingDate()->format('Y-m-d H:i:s'),
            'checkInDate' => $booking->getCheckInDate()->format('Y-m-d H:i:s'),
            'checkOutDate' => $booking->getCheckOutDate()->format('Y-m-d H:i:s'),
            'numberOfNight' => $booking->getNumberOfNight(),
            'TotalBooking' => $booking->getTotalBooking(),
            'priceByNight' => $booking->getPriceByNight(),
            'status' => $booking->getStatus(),
            // Ajoutez d'autres attributs que vous souhaitez inclure
            'user' => $booking->getUser() ? $booking->getUser()->getId() : null,
            'announce' => $booking->getAnnounce() ? $booking->getAnnounce()->getId() : null,
            'ratings' => $booking->getRatings()->map(function ($rating) {
                return $rating->getId();
            })->toArray(),
        ];

        // Retournez une réponse JSON avec les données de la réservation.
        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/edit", name="api_booking_edit", methods={"PUT"})
     */
    public function edit(Request $request, Booking $booking): JsonResponse
    {
        // Récupérez les données POST du corps de la demande.
        $data = json_decode($request->getContent(), true);

        // Mettez à jour les attributs de la réservation avec les nouvelles données.
        $booking->setBookingDate(new \DateTime($data['bookingDate']));
        $booking->setCheckInDate(new \DateTime($data['checkInDate']));
        $booking->setCheckOutDate(new \DateTime($data['checkOutDate']));
        $booking->setNumberOfNight($data['numberOfNight']);
        $booking->setTotalBooking($data['TotalBooking']);
        $booking->setPriceByNight($data['priceByNight']);
        $booking->setStatus($data['status']);
        // Mettez à jour d'autres attributs selon vos besoins.

        // Associez l'utilisateur et l'annonce aux réservations (relations ManyToOne).
        $user = $this->getDoctrine()->getRepository(User::class)->find($data['user']);
        $announce = $this->getDoctrine()->getRepository(Announce::class)->find($data['announce']);
        $booking->setUser($user);
        $booking->setAnnounce($announce);

        // Persistez la mise à jour de la réservation dans la base de données.
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Retournez une réponse JSON appropriée pour indiquer que la réservation a été mise à jour avec succès.
        return $this->json(['message' => 'Booking updated'], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_booking_delete", methods={"DELETE"})
     */
    public function delete(Booking $booking): JsonResponse
    {
        // Supprimez la réservation de la base de données.
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($booking);
        $entityManager->flush();

        // Retournez une réponse JSON appropriée pour indiquer que la réservation a été supprimée avec succès.
        return $this->json(['message' => 'Booking deleted'], Response::HTTP_NO_CONTENT);
    }
}