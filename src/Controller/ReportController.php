<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ScheduleRepository;
use App\Repository\TrainerRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    private UserRepository $userRepository;
    private ScheduleRepository $scheduleRepository;
    private TrainerRepository $trainerRepository;

    public function __construct(
        UserRepository $userRepository,
        ScheduleRepository $scheduleRepository,
        TrainerRepository $trainerRepository
    ) {
        $this->userRepository = $userRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->trainerRepository = $trainerRepository;
    }

    #[Route('/report', name: 'report_generate', methods: ['POST'])]
    public function generateReport(Request $request): Response
    {
        $name = $request->request->get('name');

        if (!in_array($name, ['schedules', 'trainers', 'users'], true)) {
            return new Response('Неверный тип отчёта', Response::HTTP_BAD_REQUEST);
        }

        switch ($name) {
            case 'schedules':
                $reportName = 'Тренировки';
                $data = $this->scheduleRepository->findAll();
                $columnsMap = [
                    'id' => 'ID',
                    'trainer_id' => 'ID тренера',
                    'datetime' => 'Дата и время',
                    'type' => 'Тип',
                ];
                break;

            case 'trainers':
                $reportName = 'Тренеры';
                $data = $this->trainerRepository->findWithoutPhoto();
                $columnsMap = [
                    'id' => 'ID',
                    'lastName' => 'Фамилия',
                    'firstName' => 'Имя',
                    'surname' => 'Отчество',
                    'phone' => 'Номер телефона',
                    'specialization' => 'Специализация',
                ];
                break;

            case 'users':
                $reportName = 'Пользователи';
                $data = $this->userRepository->findAll();
                $columnsMap = [
                    'id' => 'ID',
                    'lastName' => 'Фамилия',
                    'firstName' => 'Имя',
                    'surname' => 'Отчество',
                    'phone' => 'Номер телефона',
                    'role' => 'Роль',
                ];
                break;
        }

        $html = $this->renderView('pdf_template.html.twig', [
            'name' => $reportName,
            'data' => $data,
            'columnsMap' => $columnsMap,
        ]);

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->render();

        $pdfContent = $dompdf->output();

        return new Response(
            $pdfContent,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "inline; filename=\"report_{$name}.pdf\"",
            ]
        );
    }
}
