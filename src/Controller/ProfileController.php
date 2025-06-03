<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/profile', name: 'app_profile', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $session = $request->getSession();

        if (!$session->has('user_id')) {
            return $this->redirectToRoute('auth_login_form');
        }

        $userId = $session->get('user_id');
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            $session->clear();
            return $this->redirectToRoute('auth_login_form');
        }

        if ($user['role'] === 'client') {
            $bookings = $this->userRepository->getBookings($userId);
            return $this->render('profile/index.html.twig', [
                'user' => $user,
                'bookings' => $bookings,
            ]);
        } elseif ($user['role'] === 'admin') {
            return $this->render('profile/index.html.twig', [
                'user' => $user,
            ]);
        }

        throw $this->createAccessDeniedException('Доступ запрещен');
    }

    #[Route('/profile-redirect', name: 'app_redirect_to_profile', methods: ['GET'])]
    public function redirectToProfile(Request $request): Response
    {
        $session = $request->getSession();

        if ($session->has('user_id')) {
            return $this->redirectToRoute('app_profile');
        } else {
            return $this->redirectToRoute('auth_login_form');
        }
    }
}
