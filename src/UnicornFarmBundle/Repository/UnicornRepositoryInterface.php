<?php

namespace UnicornFarmBundle\Repository;


use UnicornFarmBundle\Entity\Unicorn;

interface UnicornRepositoryInterface
{
    /**
     * @param int $unicornId
     * @return Unicorn
     */
    public function findById($unicornId);

    /**
     * @return array
     */
    public function findAll();

    /**
     * @param Unicorn $unicorn
     */
    public function save(Unicorn $unicorn);

    /**
     * @param Unicorn $unicorn
     */
    public function delete(Unicorn $unicorn);
}