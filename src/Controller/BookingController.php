<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Schedule;
use App\Repository\BookingRepository;
use App\Repository\ScheduleRepository;
use App\Repository\TrainerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BookingController extends AbstractController
{
    private BookingRepository $bookingRepository;
    private TrainerRepository $trainerRepository;
    private ScheduleRepository $scheduleRepository;
    public function __construct(
        BookingRepository $bookingRepository,
        TrainerRepository $trainerRepository,
        ScheduleRepository $scheduleRepository
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->trainerRepository = $trainerRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    #[Route('/booking', name: 'booking_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $session = $request->getSession();

        if (!$session->has('user_id')) {
            return $this->redirectToRoute('auth_login_form');
        }

        $schedules = $this->scheduleRepository->getAllGroup();
        $trainers = $this->trainerRepository->findAll();

        return $this->render('booking/index.html.twig', [
            'schedules' => $schedules,
            'trainers' => $trainers,
        ]);
    }

    #[Route('/booking/existing', name: 'booking_add_existing', methods: ['POST'])]
    public function addToExisting(Request $request): JsonResponse
    {
        $session = $request->getSession();

        if (!$session->has('user_id')) {
            return $this->json([
                'success' => false,
                'message' => 'Требуется авторизация'
            ], 401);
        }

        $userId = $session->get('user_id');
        $scheduleId = strip_tags($request->request->get('schedule_id', ''));

        if ($this->bookingRepository->exists($userId, $scheduleId)) {
            return $this->json([
                'success' => false,
                'message' => 'Вы уже записаны на эту тренировку'
            ]);
        }

        $success = $this->bookingRepository->add($userId, $scheduleId);

        if ($success) {
            return $this->json([
                'success' => true,
                'message' => 'Вы успешно записались на тренировку'
            ]);
        }

        return $this->json([
            'success' => false,
            'message' => 'Ошибка при записи'
        ]);
    }

    #[Route('/booking/individual', name: 'booking_add_individual', methods: ['POST'])]
    public function addToIndividual(Request $request): JsonResponse
    {
        $session = $request->getSession();

        if (!$session->has('user_id')) {
            return $this->json([
                'success' => false,
                'message' => 'Требуется авторизация'
            ], 401);
        }

        $userId = $session->get('user_id');
        $datetime = strip_tags($request->request->get('datetime', ''));
        $trainerId = strip_tags($request->request->get('trainer_id', ''));

        if (empty($datetime) || empty($trainerId)) {
            return $this->json([
                'success' => false,
                'message' => 'Заполните обязательные поля ввода'
            ]);
        }

        $datetimeInput = date_create($datetime);
        if (!$datetimeInput) {
            return $this->json([
                'success' => false,
                'message' => 'Введены некорректные данные'
            ]);
        }

        $schedule = $this->scheduleRepository->add($trainerId, $datetimeInput, 'individual');
        $scheduleId = $schedule->getId();
        $this->bookingRepository->add($userId, $scheduleId);

        return $this->json([
            'success' => true,
            'message' => 'Вы успешно записались на тренировку'
        ]);
    }

    #[Route('/booking/cancel', name: 'booking_cancel', methods: ['POST'])]
    public function cancelBook(Request $request): JsonResponse
    {
        $bookingId = strip_tags($request->request->get('booking_id', ''));

        $scheduleId = $this->bookingRepository->getScheduleId($bookingId);
        $type = $this->scheduleRepository->getTypeById($scheduleId);

        $this->bookingRepository->deleteById($bookingId);

        if ($type == 'individual') {
            $this->scheduleRepository->delete($scheduleId);
        }

        return $this->json([
            'success' => true,
            'message' => 'Ваша запись успешно отменена'
        ]);
    }

    #[Route('/book-redirect', name: 'booking_redirect', methods: ['GET'])]
    public function redirectToBooking(Request $request): Response
    {
        $session = $request->getSession();

        if ($session->has('user_id')) {
            return $this->redirectToRoute('booking_index');
        }

        return $this->redirectToRoute('auth_login_form');
    }
}
