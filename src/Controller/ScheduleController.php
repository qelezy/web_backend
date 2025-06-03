<?php
namespace App\Controller;

use App\Entity\Schedule;
use App\Repository\ScheduleRepository;
use App\Repository\TrainerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleController extends AbstractController
{
    private ScheduleRepository $scheduleRepository;
    private TrainerRepository $trainerRepository;

    public function __construct(ScheduleRepository $scheduleRepository, TrainerRepository $trainerRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->trainerRepository = $trainerRepository;
    }

    #[Route('/schedules/add', name: 'schedule_form', methods: ['GET'])]
    public function form(): Response
    {
        $trainers = $this->trainerRepository->findAll();

        return $this->render('schedule/form.html.twig', [
            'trainers' => $trainers,
        ]);
    }

    #[Route('/schedules', name: 'schedule_table', methods: ['GET'])]
    public function table(): Response
    {
        $columnsMap = [
            'id' => 'ID',
            'trainer_id' => 'ID тренера',
            'datetime' => 'Дата и время',
            'type' => 'Тип',
        ];

        $schedules = $this->scheduleRepository->findAll();

        return $this->render('schedule/table.html.twig', [
            'schedules' => $schedules,
            'columnsMap' => $columnsMap,
        ]);
    }

    #[Route('/schedules/add', name: 'schedule_save', methods: ['POST'])]
    public function save(Request $request): JsonResponse
    {
        $trainerId = strip_tags($request->request->get('trainer_id', ''));
        $datetime = strip_tags($request->request->get('datetime', ''));
        $type = strip_tags($request->request->get('type', ''));

        if (empty($trainerId) || empty($datetime) || empty($type)) {
            return new JsonResponse(['message' => 'Заполните обязательные поля ввода'], 400);
        }

        $trainerIdInput = intval($trainerId);
        $datetimeInput = date_create($datetime);

        if (!$trainerIdInput || !$datetimeInput || !in_array($type, ['individual', 'group'])) {
            return new JsonResponse(['message' => 'Введены некорректные данные'], 400);
        }

        if ($trainerIdInput <= 0) {
            return new JsonResponse(['message' => 'Некорректный ID'], 400);
        }

        if ($this->scheduleRepository->add($trainerIdInput, $datetimeInput, $type)) {
            return new JsonResponse(['message' => 'Тренировка успешно добавлена']);
        } else {
            return new JsonResponse(['message' => 'Ошибка при выполнении запроса'], 500);
        }
    }
}
