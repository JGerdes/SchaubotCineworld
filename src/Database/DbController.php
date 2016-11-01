<?php

namespace JGerdes\SchauBot\Database;


use Doctrine\ORM\EntityManager;

class DbController {

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }
}