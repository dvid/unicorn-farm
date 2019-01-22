<?php

namespace UnicornFarmBundle\Service;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use UnicornFarmBundle\Entity\User;
use UnicornFarmBundle\Repository\UserRepository;

final class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $this->entityManager->getRepository(User::class);
    }

    public function getUser($userId)
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new EntityNotFoundException('User with id does not exist!');
        }
        return $user;
    }

    public function getUserByName($firstName, $lastName)
    {
        $user = $this->userRepository->findByUserName($firstName, $lastName);
        if (!$user) {
            throw new EntityNotFoundException('User with id does not exist!');
        }
        return $user;
    }

    public function getAllUsers()
    {
        return $this->userRepository->findAll();
    }

    public function addUser($firstName, $lastName, $email)
    {
        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $this->userRepository->save($user);
        return $user;
    }

    public function updateUser($userId, $name, $description)
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            return null;
        }
        $user->setName($name);
        $user->setDescription($description);
        $this->userRepository->save($user);
        return $user;
    }

    public function deleteUser($userId)
    {
        $user = $this->userRepository->findById($userId);
        if ($user) {
            $this->userRepository->delete($user);
        }
    }
}