<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
/**
 * @Route("/api/login", name="api_login", methods={"POST"})
*/
public function login(JWTTokenManagerInterface $tokenManager,UserRepository $userRepository,Request $request, UserPasswordEncoderInterface $encoder, JWTTokenManagerInterface $JWTManager, AuthenticationUtils $authenticationUtils)
{
    $data = json_decode($request->getContent(), true);

    if (!isset($data['username']) || !isset($data['password'])) {
        return new JsonResponse(['error' => 'Invalid JSON data'], 400);
    }
    
    $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $data['username']]);
    if (!$user || !$encoder->isPasswordValid($user, $data['password'])) {
        throw new BadCredentialsException();
    }
    
    // Générer le token JWT
    // $tokenManager = $this->get(JWTTokenManagerInterface::class);
    $token = $tokenManager->create($user);
    
    return new JsonResponse(['token' => $token]);
    
}
}

