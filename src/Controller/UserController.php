<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/admins/add', name: 'user_admin_form', methods: ['GET'])]
    public function adminForm(): Response
    {
        return $this->render('user/admin_form.html.twig');
    }

    #[Route('/users', name: 'user_table', methods: ['GET'])]
    public function table(): Response
    {
        $columnsMap = [
            "id" => "ID",
            "lastName" => "Фамилия",
            "firstName" => "Имя",
            "surname" => "Отчество",
            "phone" => "Номер телефона",
            "role" => "Роль"
        ];

        $users = $this->userRepository->findAll();

        return $this->render('user/users_table.html.twig', [
            'users' => $users,
            'columnsMap' => $columnsMap
        ]);
    }

    #[Route('/admins/add', name: 'user_save_admin', methods: ['POST'])]
    public function saveAdmin(Request $request): JsonResponse
    {
        $lastName = strip_tags($request->request->get('last_name', ''));
        $firstName = strip_tags($request->request->get('first_name', ''));
        $surname = strip_tags($request->request->get('surname', ''));
        $phone = strip_tags($request->request->get('phone', ''));
        $password = strip_tags($request->request->get('password', ''));
        $repeatPassword = strip_tags($request->request->get('repeat_password', ''));

        if (empty($lastName) || empty($firstName) || empty($phone) || empty($password) || empty($repeatPassword)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Заполните обязательные поля ввода'
            ], 400);
        }

        if ($password !== $repeatPassword) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Пароли не совпадают'
            ], 400);
        }

        $phoneFormat = "/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/";
        if (!preg_match($phoneFormat, $phone)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Введены некорректные данные'
            ], 400);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = 'admin';

        $success = $this->userRepository->add($lastName, $firstName, $surname, $phone, $hashedPassword, $role);

        if ($success) {
            return new JsonResponse(['message' => 'Администратор успешно добавлен']);
        }

        return new JsonResponse(['message' => 'Ошибка при выполнении запроса'], 500);
    }
}
