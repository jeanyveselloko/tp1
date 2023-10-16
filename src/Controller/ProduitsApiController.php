<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api/produits")
 */
class ProduitsApiController extends AbstractController
{

    /**
     * @Route("/", name="api_produits_index", methods={"GET"})
     */
    public function index(ProduitsRepository $produitsRepository): JsonResponse
    {
        $produits = $produitsRepository->findAll();
        $data = [];

        foreach ($produits as $produit) {
            $data[] = [
                'id' => $produit->getId(),
                'name' => $produit->getNom(),
                'manufacturer' => $produit->getFabriquant(),
                'description' => $produit->getDescription(),
                'price' => $produit->getPrix(),
                
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }


    /**
     * @Route("/new", name="api_produits_new", methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $produit = new Produits();
        $produit->setNom($data['name']);
        $produit->setFabriquant($data['manufacturer']);
        $produit->setDescription($data['description']);
        $produit->setPrix($data['price']);
     

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produit);
        $entityManager->flush();

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="api_produits_show", methods={"GET"})
     */
    public function show(Produits $produit,ProduitsRepository $produitsRepository): JsonResponse
    {
        $produit = $produitsRepository->find($id);
        $data = [
            'id' => $produit->getId(),
            'name' => $produit->getNom(),
            'manufacturer' => $produit->getFabriquant(),
            'description' => $produit->getDescription(),
            'price' => $produit->getPrix(),

        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/edit", name="api_produits_edit", methods={"PUT"})
     */
    public function edit(Request $request, Produits $produit): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $produit->setNom($data['name']);
        $produit->setFabriquant($data['manufacturer']);
        $produit->setDescription($data['description']);
        $produit->setPrix($data['price']);
 

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new JsonResponse($data, Response::HTTP_OK);
    }

    // Exemple : Suppression d'un produit par ID
    /**
     * @Route("/{id}", name="api_produits_delete", methods={"DELETE"})
     */
    public function delete(Produits $produit): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($produit);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Produit supprimé'], Response::HTTP_NO_CONTENT);
    }
}