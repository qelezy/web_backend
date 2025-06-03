<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\Schedule;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getScheduleId(int $bookingId): ?int
    {
        $booking = $this->find($bookingId);
        return $booking?->getSchedule()?->getId();
    }

    public function add(int $userId, int $scheduleId): bool
    {
        try {
            $user = $this->getEntityManager()->getRepository(User::class)->find($userId);
            $schedule = $this->getEntityManager()->getRepository(Schedule::class)->find($scheduleId);

            if (!$user || !$schedule) {
                return false;
            }

            $booking = new Booking();
            $booking->setUser($user);
            $booking->setSchedule($schedule);

            $this->getEntityManager()->persist($booking);
            $this->getEntityManager()->flush();

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function exists(int $userId, int $scheduleId): bool
    {
        return $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->where('b.user = :userId')
            ->andWhere('b.schedule = :scheduleId')
            ->setParameter('userId', $userId)
            ->setParameter('scheduleId', $scheduleId)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    public function deleteById(int $bookingId): bool
    {
        $booking = $this->find($bookingId);
        if ($booking) {
            $this->getEntityManager()->remove($booking);
            $this->getEntityManager()->flush();
            return true;
        }
        return false;
    }
}
