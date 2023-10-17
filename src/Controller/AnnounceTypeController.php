<?php

namespace App\Controller;

use App\Entity\AnnounceType;
use App\Form\AnnounceTypeType; // Assurez-vous d'avoir le bon formulaire
use App\Repository\AnnounceTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;;

/**
 * @Route("/api/announce_types")
 */
class AnnounceTypeController extends AbstractController
{ /**
    * @Route("/", name="api_announce_type_index", methods={"GET"})
    */
   public function index(AnnounceTypeRepository $announceTypeRepository): JsonResponse
   {
       $announceTypes = $announceTypeRepository->findAll();
       $data = [];

       foreach ($announceTypes as $announceType) {
           $data[] = [
               'id' => $announceType->getId(),
               'name' => $announceType->getName(),
               // Ajoutez d'autres attributs que vous souhaitez inclure
           ];
       }

       return new JsonResponse($data, Response::HTTP_OK);
   }

   /**
    * @Route("/new", name="api_announce_type_new", methods={"POST"})
    */
   public function new(Request $request): JsonResponse
   {
       // Récupérez les données POST du corps de la demande.
       $data = json_decode($request->getContent(), true);
       
       // Créez une nouvelle instance de AnnounceType et attribuez les valeurs des données.
       $announceType = new AnnounceType();
       $announceType->setName($data['name']);
       // Définissez d'autres attributs selon vos besoins.

       // Persistez la nouvelle instance de AnnounceType dans la base de données.
       $entityManager = $this->getDoctrine()->getManager();
       $entityManager->persist($announceType);
       $entityManager->flush();

       // Retournez une réponse JSON appropriée pour indiquer que l'AnnounceType a été créé avec succès.
       return $this->json(['message' => 'AnnounceType created'], Response::HTTP_CREATED);
   }

   /**
    * @Route("/{id}", name="api_announce_type_show", methods={"GET"})
    */
   public function show(AnnounceType $announceType): JsonResponse
   {
       // Créez un tableau de données pour représenter l'AnnounceType.
       $data = [
           'id' => $announceType->getId(),
           'name' => $announceType->getName(),
           // Ajoutez d'autres attributs que vous souhaitez inclure
       ];

       // Retournez une réponse JSON avec les données de l'AnnounceType.
       return $this->json($data, Response::HTTP_OK);
   }

   /**
    * @Route("/{id}/edit", name="api_announce_type_edit", methods={"PUT"})
    */
   public function edit(Request $request, AnnounceType $announceType): JsonResponse
   {
       // Récupérez les données POST du corps de la demande.
       $data = json_decode($request->getContent(), true);

       // Mettez à jour les attributs de l'AnnounceType avec les nouvelles données.
       $announceType->setName($data['name']);
       // Mettez à jour d'autres attributs selon vos besoins.

       // Persistez la mise à jour de l'AnnounceType dans la base de données.
       $entityManager = $this->getDoctrine()->getManager();
       $entityManager->flush();

       // Retournez une réponse JSON appropriée pour indiquer que l'AnnounceType a été mis à jour avec succès.
       return $this->json(['message' => 'AnnounceType updated'], Response::HTTP_OK);
   }

   /**
    * @Route("/{id}", name="api_announce_type_delete", methods={"DELETE"})
    */
   public function delete(AnnounceType $announceType): JsonResponse
   {
       // Supprimez l'AnnounceType de la base de données.
       $entityManager = $this->getDoctrine()->getManager();
       $entityManager->remove($announceType);
       $entityManager->flush();

       // Retournez une réponse JSON appropriée pour indiquer que l'AnnounceType a été supprimé avec succès.
       return $this->json(['message' => 'AnnounceType deleted'], Response::HTTP_NO_CONTENT);
   }
}