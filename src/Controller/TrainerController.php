<?php
namespace App\Controller;

use App\Repository\TrainerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrainerController extends AbstractController
{
    private TrainerRepository $trainerRepository;

    public function __construct(TrainerRepository $trainerRepository)
    {
        $this->trainerRepository = $trainerRepository;
    }

    #[Route('/trainers/add', name: 'trainer_form', methods: ['GET'])]
    public function form(): Response
    {
        return $this->render('trainer/form.html.twig');
    }

    #[Route('/trainers', name: 'trainer_table', methods: ['GET'])]
    public function table(): Response
    {
        $columnsMap = [
            "id" => "ID",
            "photo" => "Фото",
            "lastName" => "Фамилия",
            "firstName" => "Имя",
            "surname" => "Отчество",
            "phone" => "Номер телефона",
            "specialization" => "Специализация"
        ];

        $trainers = $this->trainerRepository->findAll();

        return $this->render('trainer/table.html.twig', [
            'trainers' => $trainers,
            'columnsMap' => $columnsMap
        ]);
    }

    #[Route('/trainers/add', name: 'trainer_save', methods: ['POST'])]
    public function save(Request $request): JsonResponse
    {
        $lastName = strip_tags($request->request->get('last_name', ''));
        $firstName = strip_tags($request->request->get('first_name', ''));
        $surname = strip_tags($request->request->get('surname', ''));
        $phone = strip_tags($request->request->get('phone', ''));
        $specialization = strip_tags($request->request->get('specialization', ''));
        $birthDate = strip_tags($request->request->get('birth_date', ''));

        if (empty($lastName) || empty($firstName) || empty($phone) || empty($specialization)) {
            return new JsonResponse(['message' => 'Заполните обязательные поля ввода'], 400);
        }

        $nameFormat = "/^[a-zA-Zа-яА-ЯёЁ\s]*(?:-[a-zA-Zа-яА-ЯёЁ\s]*)?$/";
        $phoneFormat = "/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/";
        $specializationFormat = "/^[a-zA-Zа-яА-ЯёЁ\s]+$/";
        $dateInput = $birthDate ? date_create($birthDate) : null;

        if (!preg_match($nameFormat, $lastName) || 
            !preg_match($nameFormat, $firstName) ||
            !preg_match($phoneFormat, $phone) || 
            !preg_match($specializationFormat, $specialization) || 
            ($birthDate && !$dateInput)) {
            return new JsonResponse(['message' => 'Введены некорректные данные'], 400);
        }

        $success = $this->trainerRepository->add($lastName, $firstName, $surname, $phone, $specialization);

        if ($success) {
            return new JsonResponse(['message' => 'Тренер успешно добавлен']);
        }

        return new JsonResponse(['message' => 'Ошибка при выполнении запроса'], 500);
    }
}
