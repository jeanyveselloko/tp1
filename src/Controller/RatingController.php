<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\RatingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/ratings")
 */
class RatingController extends AbstractController
{
      /**
     * @Route("/", name="api_rating_index", methods={"GET"})
     */
    public function index(RatingRepository $ratingRepository): JsonResponse
    {
        $ratings = $ratingRepository->findAll();
        $data = [];

        foreach ($ratings as $rating) {
            $data[] = [
                'id' => $rating->getId(),
                'rate' => $rating->getRate(),
                'comment' => $rating->getComment(),
                'createAt' => $rating->getCreateAt()->format('Y-m-d H:i:s'),
                'modifieAt' => $rating->getModifieAt()->format('Y-m-d H:i:s'),
                'booking' => $rating->getBooking()->getId(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="api_rating_new", methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $rating = new Rating();
        $rating->setRate($data['rate']);
        $rating->setComment($data['comment']);
        $rating->setCreateAt(new \DateTime());
        $rating->setModifieAt(new \DateTime());
        
        // Récupérez la réservation associée en fonction de l'ID fourni dans les données
        $bookingId = $data['booking'];
        $booking = $this->getDoctrine()
            ->getRepository(Booking::class)
            ->find($bookingId);

        if (!$booking) {
            return new JsonResponse(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        $rating->setBooking($booking);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rating);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Rating created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="api_rating_show", methods={"GET"})
     */
    public function show(Rating $rating): JsonResponse
    {
        $data = [
            'id' => $rating->getId(),
            'rate' => $rating->getRate(),
            'comment' => $rating->getComment(),
            'createAt' => $rating->getCreateAt()->format('Y-m-d H:i:s'),
            'modifieAt' => $rating->getModifieAt()->format('Y-m-d H:i:s'),
            'booking' => $rating->getBooking()->getId(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/edit", name="api_rating_edit", methods={"PUT"})
     */
    public function edit(Request $request, Rating $rating): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $rating->setRate($data['rate']);
        $rating->setComment($data['comment']);
        $rating->setModifieAt(new \DateTime());
        
        // Récupérez la réservation associée en fonction de l'ID fourni dans les données
        $bookingId = $data['booking'];
        $booking = $this->getDoctrine()
            ->getRepository(Booking::class)
            ->find($bookingId);

        if (!$booking) {
            return new JsonResponse(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        $rating->setBooking($booking);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new JsonResponse(['message' => 'Rating updated'], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_rating_delete", methods={"DELETE"})
     */
    public function delete(Rating $rating): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($rating);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Rating deleted'], Response::HTTP_NO_CONTENT);
    }
}