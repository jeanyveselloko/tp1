<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="api_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'roles' => $user->getRoles(),
                'firstname' => $user->getFirstname(),
                'surname' => $user->getSurname(),
                'email' => $user->getEmail(),
                'phoneNumber' => $user->getPhoneNumber(),
                'biography' => $user->getBiography(),
                'profilePicture' => $user->getProfilePicture(),
                'birthDate' => $user->getBirthDate()->format('Y-m-d'),
                'announces' => $user->getAnnounces()->map(function ($announce) {
                    return $announce->getId();
                })->toArray(),
                'bookings' => $user->getBookings()->map(function ($booking) {
                    return $booking->getId();
                })->toArray(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="api_user_new", methods={"POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setUsername($data['username']);
        $user->setRoles(['ROLES_USER']);
       // Utilisez le service UserPasswordEncoderInterface pour hacher le mot de passe
    $hashedPassword = $passwordEncoder->encodePassword($user, $data['password']);
    $user->setPassword($hashedPassword);
        $user->setFirstname($data['firstname']);
        $user->setSurname($data['surname']);
        $user->setEmail($data['email']);
        $user->setPhoneNumber($data['phoneNumber']);
        $user->setBiography($data['biography']);
        $user->setProfilePicture($data['profilePicture']);
        $user->setBirthDate(new \DateTime($data['birthDate']));
        // Ajoutez des valeurs pour les annonces et les réservations si nécessaire

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'User created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="api_user_show", methods={"GET"})
     */
    public function show(User $user): JsonResponse
    {
        $data = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'firstname' => $user->getFirstname(),
            'surname' => $user->getSurname(),
            'email' => $user->getEmail(),
            'phoneNumber' => $user->getPhoneNumber(),
            'biography' => $user->getBiography(),
            'profilePicture' => $user->getProfilePicture(),
            'birthDate' => $user->getBirthDate()->format('Y-m-d'),
            'announces' => $user->getAnnounces()->map(function ($announce) {
                return $announce->getId();
            })->toArray(),
            'bookings' => $user->getBookings()->map(function ($booking) {
                return $booking->getId();
            })->toArray(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/edit", name="api_user_edit", methods={"PUT"})
     */
    public function edit(Request $request, User $user): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user->setUsername($data['username']);
        $user->setRoles($data['roles']);
        $user->setPassword($data['password']);
        $user->setFirstname($data['firstname']);
        $user->setSurname($data['surname']);
        $user->setEmail($data['email']);
        $user->setPhoneNumber($data['phoneNumber']);
        $user->setBiography($data['biography']);
        $user->setProfilePicture($data['profilePicture']);
        $user->setBirthDate(new \DateTime($data['birthDate']));
        // Ajoutez des valeurs pour les annonces et les réservations si nécessaire

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new JsonResponse(['message' => 'User updated'], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_user_delete", methods={"DELETE"})
     */
    public function delete(User $user): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'User deleted'], Response::HTTP_NO_CONTENT);
    }
}
