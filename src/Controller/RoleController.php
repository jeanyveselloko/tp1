<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/roles")
 */
class RoleController extends AbstractController
{
  /**
     * @Route("/", name="api_role_index", methods={"GET"})
     */
    public function index(RoleRepository $roleRepository): JsonResponse
    {
        $roles = $roleRepository->findAll();
        $data = [];

        foreach ($roles as $role) {
            $data[] = [
                'id' => $role->getId(),
                'name' => $role->getName(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="api_role_new", methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $role = new Role();
        $role->setName($data['name']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($role);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Role created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="api_role_show", methods={"GET"})
     */
    public function show(Role $role): JsonResponse
    {
        $data = [
            'id' => $role->getId(),
            'name' => $role->getName(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/edit", name="api_role_edit", methods={"PUT"})
     */
    public function edit(Request $request, Role $role): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $role->setName($data['name']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new JsonResponse(['message' => 'Role updated'], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_role_delete", methods={"DELETE"})
     */
    public function delete(Role $role): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($role);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Role deleted'], Response::HTTP_NO_CONTENT);
    }
}