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

    public function searchMovieByTitle($query) {
        $result = $this->entityManager
            ->getRepository("JGerdes\SchauBot\Entity\Movie")
            ->createQueryBuilder('m')
            ->where('m.title LIKE :title')
            ->setParameter('title', '%' . $query . '%')
            ->getQuery()
            ->getResult();

        if (sizeof($result) == 0) {
            return null;
        } else {
            return $result[0];
        }
    }

    public function findScreeningsByMovie($movie) {
        if ($movie === null) {
            return [];
        } else {
            return $this->entityManager
                ->getRepository('JGerdes\SchauBot\Entity\Screening')
                ->findBy(array(
                    'movie' => $movie
                ));
        }
    }

    public function findScreeningsBetweenDates($from, $to) {
        return $this->entityManager
            ->getRepository('JGerdes\SchauBot\Entity\Screening')
            ->createQueryBuilder("e")
            ->where('e.time BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()->getResult();
    }

    public function findScreeningsForDate($date) {
        $from = new \DateTime($date->format("Y-m-d") . " 00:00:00");
        $to = new \DateTime($date->format("Y-m-d") . " 23:59:59");

        return $this->findScreeningsBetweenDates($from, $to);
    }

    public function findScreeningsForAndBetweenDates($from, $to) {
        $from = new \DateTime($from->format("Y-m-d") . " 00:00:00");
        $to = new \DateTime($to->format("Y-m-d") . " 23:59:59");

        return $this->findScreeningsBetweenDates($from, $to);
    }
}