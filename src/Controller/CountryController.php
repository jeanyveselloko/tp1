<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType; // Assurez-vous d'avoir le bon formulaire
use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/countries")
 */
class CountryController extends AbstractController
{  /**
    * @Route("/", name="api_country_index", methods={"GET"})
    */
   public function index(CountryRepository $countryRepository): JsonResponse
   {
       $countries = $countryRepository->findAll();
       $data = [];

       foreach ($countries as $country) {
           $data[] = [
               'id' => $country->getId(),
               'name' => $country->getName(),
               // Ajoutez d'autres attributs que vous souhaitez inclure
           ];
       }

       return new JsonResponse($data, Response::HTTP_OK);
   }

   /**
    * @Route("/new", name="api_country_new", methods={"POST"})
    */
   public function new(Request $request): JsonResponse
   {
       // Récupérez les données POST du corps de la demande.
       $data = json_decode($request->getContent(), true);

       // Créez une nouvelle instance de Country et attribuez les valeurs des données.
       $country = new Country();
       $country->setName($data['name']);
       // Définissez d'autres attributs selon vos besoins.

       // Persistez la nouvelle instance de Country dans la base de données.
       $entityManager = $this->getDoctrine()->getManager();
       $entityManager->persist($country);
       $entityManager->flush();

       // Retournez une réponse JSON appropriée pour indiquer que le pays a été créé avec succès.
       return $this->json(['message' => 'Country created'], Response::HTTP_CREATED);
   }

   /**
    * @Route("/{id}", name="api_country_show", methods={"GET"})
    */
   public function show(Country $country): JsonResponse
   {
       // Créez un tableau de données pour représenter le pays.
       $data = [
           'id' => $country->getId(),
           'name' => $country->getName(),
           // Ajoutez d'autres attributs que vous souhaitez inclure
       ];

       // Retournez une réponse JSON avec les données du pays.
       return $this->json($data, Response::HTTP_OK);
   }

   /**
    * @Route("/{id}/edit", name="api_country_edit", methods={"PUT"})
    */
   public function edit(Request $request, Country $country): JsonResponse
   {
       // Récupérez les données POST du corps de la demande.
       $data = json_decode($request->getContent(), true);

       // Mettez à jour les attributs du pays avec les nouvelles données.
       $country->setName($data['name']);
       // Mettez à jour d'autres attributs selon vos besoins.

       // Persistez la mise à jour du pays dans la base de données.
       $entityManager = $this->getDoctrine()->getManager();
       $entityManager->flush();

       // Retournez une réponse JSON appropriée pour indiquer que le pays a été mis à jour avec succès.
       return $this->json(['message' => 'Country updated'], Response::HTTP_OK);
   }

   /**
    * @Route("/{id}", name="api_country_delete", methods={"DELETE"})
    */
   public function delete(Country $country): JsonResponse
   {
       // Supprimez le pays de la base de données.
       $entityManager = $this->getDoctrine()->getManager();
       $entityManager->remove($country);
       $entityManager->flush();

       // Retournez une réponse JSON appropriée pour indiquer que le pays a été supprimé avec succès.
       return $this->json(['message' => 'Country deleted'], Response::HTTP_NO_CONTENT);
   }
}