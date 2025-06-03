<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/signup', name: 'auth_signup_form', methods: ['GET'])]
    public function signupForm(): Response
    {
        return $this->render('auth/signup.html.twig');
    }

    #[Route('/login', name: 'auth_login_form', methods: ['GET'])]
    public function loginForm(): Response
    {
        return $this->render('auth/login.html.twig');
    }

    #[Route('/auth/check', name: 'auth_check', methods: ['POST'])]
    public function authCheck(Request $request): JsonResponse
    {
        $session = $request->getSession();

        $phone = strip_tags($request->request->get('phone', ''));
        $password = strip_tags($request->request->get('password', ''));

        if (empty($phone) || empty($password)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Заполните обязательные поля ввода',
            ]);
        }

        if (!preg_match('/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/', $phone)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Введены некорректные данные',
            ]);
        }

        $user = $this->userRepository->findByPhone($phone);

        if ($user && password_verify($password, $user['password'])) {
            $session->set('user_id', $user['id']);
            $session->set('role', $user['role']);

            return new JsonResponse([
                'success' => true,
                'redirect' => '/profile',
            ]);
        }

        return new JsonResponse([
            'success' => false,
            'message' => 'Неверный номер телефона или пароль',
        ]);
    }

    #[Route('/auth/signup', name: 'auth_signup', methods: ['POST'])]
    public function signup(Request $request): JsonResponse
    {
        $session = $request->getSession();

        $lastName = strip_tags($request->request->get('last_name', ''));
        $firstName = strip_tags($request->request->get('first_name', ''));
        $surname = strip_tags($request->request->get('surname', ''));
        $phone = strip_tags($request->request->get('phone', ''));
        $password = strip_tags($request->request->get('password', ''));
        $repeatPassword = strip_tags($request->request->get('repeat_password', ''));

        if (empty($lastName) || empty($firstName) || empty($phone) || empty($password) || empty($repeatPassword)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Заполните обязательные поля ввода',
            ]);
        }

        if ($password !== $repeatPassword) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Пароли не совпадают',
            ]);
        }

        if (!preg_match('/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/', $phone)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Введены некорректные данные',
            ]);
        }

        if ($this->userRepository->findByPhone($phone)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Номер телефона уже зарегистрирован',
            ]);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = 'client';

        if ($this->userRepository->add($lastName, $firstName, $surname, $phone, $hashedPassword, $role)) {
            $newUser = $this->userRepository->findByPhone($phone);
            $session->set('user_id', $newUser['user_id']);
            $session->set('role', $role);

            return new JsonResponse([
                'success' => true,
                'redirect' => '/profile',
            ]);
        }

        return new JsonResponse([
            'success' => false,
            'message' => 'Ошибка при выполнении запроса',
        ]);
    }

    #[Route('/auth/logout', name: 'auth_logout', methods: ['POST'])]
    public function logout(Request $request): JsonResponse
    {
        $session = $request->getSession();
        $session->clear();
        return new JsonResponse(['success' => true]);
    }
}
