<?php

namespace App\Repository;

use App\Entity\Schedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Schedule>
 */
class ScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Schedule::class);
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.datetime', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    public function getAllGroup(): array
    {
        return $this->createQueryBuilder('s')
        ->select('s.id AS id, s.datetime AS datetime, CONCAT(t.lastName, \' \', t.firstName) AS trainer_name, t.photo AS trainer_photo')
        ->leftJoin('s.trainer', 't')
        ->where('s.type = :type')
        ->setParameter('type', 'group')
        ->orderBy('s.datetime', 'ASC')
        ->getQuery()
        ->getArrayResult();
    }

    public function add(int $trainerId, \DateTimeInterface $datetime, string $type): Schedule
    {
        $trainer = $this->getEntityManager()->getRepository(\App\Entity\Trainer::class)->find($trainerId);
        
        if (!$trainer) {
            throw new \InvalidArgumentException('Тренер не найден');
        }

        $schedule = new \App\Entity\Schedule();
        $schedule->setTrainer($trainer);
        $schedule->setDatetime($datetime);
        $schedule->setType($type);

        $em = $this->getEntityManager();
        $em->persist($schedule);
        $em->flush();

        return $schedule;
    }

    public function delete(int $scheduleId): void
    {
        $em = $this->getEntityManager();
        $schedule = $em->getRepository(\App\Entity\Schedule::class)->find($scheduleId);

        if ($schedule) {
            $em->remove($schedule);
            $em->flush();
        }
    }

    public function getTypeById(int $id): ?string
    {
        return $this->createQueryBuilder('s')
            ->select('s.type')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
