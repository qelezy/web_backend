<?php

namespace App\Repository;

use App\Entity\Trainer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TrainerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trainer::class);
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('t')
            ->getQuery()
            ->getArrayResult();
    }

    public function getWithoutPhoto(): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.id, t.last_name, t.first_name, t.surname, t.phone, t.specialization')
            ->getQuery()
            ->getArrayResult();
    }

    public function add(string $lastName, string $firstName, $surname, string $phone, string $specialization): bool
    {
        try {
            $trainer = new Trainer();
            $trainer->setLastName($lastName);
            $trainer->setFirstName($firstName);
            $trainer->setSurname($surname);
            $trainer->setPhone($phone);
            $trainer->setSpecialization($specialization);

            $this->_em->persist($trainer);
            $this->_em->flush();

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
