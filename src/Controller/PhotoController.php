<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/photos")
 */
class PhotoController extends AbstractController
{
    /**
     * @Route("/", name="api_photo_index", methods={"GET"})
     */
    public function index(PhotoRepository $photoRepository): JsonResponse
    {
        $photos = $photoRepository->findAll();
        $data = [];

        foreach ($photos as $photo) {
            $data[] = [
                'id' => $photo->getId(),
                'storageName' => $photo->getStorageName(),
                'createAt' => $photo->getCreateAt()->format('Y-m-d'),
                'fileType' => $photo->getFileType(),
                'announce' => $photo->getAnnounce()->getId(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="api_photo_new", methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $photo = new Photo();
        $photo->setStorageName($data['storageName']);
        $photo->setCreateAt(new \DateTime($data['createAt']));
        $photo->setFileType($data['fileType']);
        
        // Récupérez l'annonce associée en fonction de l'ID fourni dans les données
        $announceId = $data['announce'];
        $announce = $this->getDoctrine()
            ->getRepository(Announce::class)
            ->find($announceId);

        if (!$announce) {
            return new JsonResponse(['message' => 'Announce not found'], Response::HTTP_NOT_FOUND);
        }

        $photo->setAnnounce($announce);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($photo);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Photo created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="api_photo_show", methods={"GET"})
     */
    public function show(Photo $photo): JsonResponse
    {
        $data = [
            'id' => $photo->getId(),
            'storageName' => $photo->getStorageName(),
            'createAt' => $photo->getCreateAt()->format('Y-m-d'),
            'fileType' => $photo->getFileType(),
            'announce' => $photo->getAnnounce()->getId(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/edit", name="api_photo_edit", methods={"PUT"})
     */
    public function edit(Request $request, Photo $photo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $photo->setStorageName($data['storageName']);
        $photo->setCreateAt(new \DateTime($data['createAt']));
        $photo->setFileType($data['fileType']);
        
        // Récupérez l'annonce associée en fonction de l'ID fourni dans les données
        $announceId = $data['announce'];
        $announce = $this->getDoctrine()
            ->getRepository(Announce::class)
            ->find($announceId);

        if (!$announce) {
            return new JsonResponse(['message' => 'Announce not found'], Response::HTTP_NOT_FOUND);
        }

        $photo->setAnnounce($announce);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new JsonResponse(['message' => 'Photo updated'], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_photo_delete", methods={"DELETE"})
     */
    public function delete(Photo $photo): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($photo);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Photo deleted'], Response::HTTP_NO_CONTENT);
    }
}