<?php

namespace UnicornFarmBundle\Service;

use Doctrine\ORM\EntityNotFoundException;
use UnicornFarmBundle\Entity\Unicorn;
use Doctrine\ORM\EntityManagerInterface;
use UnicornFarmBundle\Repository\UnicornRepository;

final class UnicornService
{
    /**
     * @var UnicornRepository
     */
    private $unicornRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->unicornRepository = $this->entityManager->getRepository(Unicorn::class);
    }

    public function getUnicorn($unicornId)
    {
        $unicorn = $this->unicornRepository->findById($unicornId);
        if (!$unicorn) {
            throw new EntityNotFoundException('Unicorn with id does not exist!');
        }
        return $unicorn;
    }

    public function getAllUnicorns()
    {
        return $this->unicornRepository->findAll();
    }

    public function addUnicorn($name, $description = "")
    {
        $unicorn = new Unicorn();
        $unicorn->setName($name);
        $unicorn->setDescription($description);
        $this->unicornRepository->save($unicorn);
        return $unicorn;
    }

    public function updateUnicorn($unicornId, $name, $description)
    {
        $unicorn = $this->unicornRepository->findById($unicornId);
        if (!$unicorn) {
            return null;
        }
        $unicorn->setName($name);
        $unicorn->setDescription($description);
        $this->unicornRepository->save($unicorn);
        return $unicorn;
    }

    public function deleteUnicorn($unicornId)
    {
        $unicorn = $this->unicornRepository->findById($unicornId);
        if ($unicorn) {
            $this->unicornRepository->delete($unicorn);
        }
    }

    public function sellUnicorn($unicornId, $userId)
    {
        $unicorn = $this->unicornRepository->findById($unicornId);
        if (!$unicorn) {
            return null;
        }
        $unicorn->setAvailable(0);
        $unicorn->setUser($userId);
        $this->unicornRepository->save($unicorn);
        return $unicorn;
    }
}