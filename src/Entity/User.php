<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 128)]
    private string $lastName;

    #[ORM\Column(type: 'string', length: 128)]
    private string $firstName;
    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private ?string $surname = null;

    #[ORM\Column(type: 'string', length: 24)]
    private string $phone;
    #[ORM\Column(type: 'string', length: 128)]
    private string $password;

    #[ORM\Column(type: 'string', columnDefinition: "ENUM('admin', 'client')")]
    private string $role = 'client';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $allowedRoles = ['admin', 'client'];
        if (!in_array($role, $allowedRoles)) {
            throw new \InvalidArgumentException("Invalid role");
        }
        $this->role = $role;
        return $this;
    }
}
