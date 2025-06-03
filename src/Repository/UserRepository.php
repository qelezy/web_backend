<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

class UserRepository extends ServiceEntityRepository
{
    private Connection $connection;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->connection = $this->getEntityManager()->getConnection();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.id, u.lastName, u.firstName, u.surname, u.phone, u.role')
            ->getQuery()
            ->getArrayResult();
    }

    public function add(string $lastName, string $firstName, ?string $surname, string $phone, string $password, string $role): bool
    {
        try {
            $user = new User();
            $user->setLastName($lastName);
            $user->setFirstName($firstName);
            $user->setSurname($surname);
            $user->setPhone($phone);
            $user->setPassword($password);
            $user->setRole($role);

            $this->_em->persist($user);
            $this->_em->flush();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function findById(int $id): ?array
    {
        return $this->createQueryBuilder('u')
            ->select('u.id', 'u.firstName', 'u.lastName', 'u.surname', 'u.phone', 'u.role')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    public function findByPhone(string $phone): ?array
    {
        return $this->createQueryBuilder('u')
            ->where('u.phone = :phone')
            ->setParameter('phone', $phone)
            ->getQuery()
            ->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    public function findUserInfoById(int $id): ?array
    {
        return $this->createQueryBuilder('u')
            ->select('u.lastName as last_name, u.firstName as first_name, u.surname, u.phone, u.role')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    public function getBookings(int $userId): array
    {
        $sql = "
            SELECT 
                b.id AS id,
                s.datetime AS datetime,
                t.photo,
                CONCAT(t.last_name, ' ', t.first_name) AS trainer_name,
                t.specialization AS specialization
            FROM booking b
            JOIN schedules s ON b.schedule_id = s.id
            JOIN trainers t ON s.trainer_id = t.id
            WHERE b.user_id = :id
            ORDER BY s.datetime
        ";

        return $this->connection->fetchAllAssociative($sql, ['id' => $userId]);
    }
}
