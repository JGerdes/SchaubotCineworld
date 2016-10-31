<?php

namespace JGerdes\Schaubot\Crawler;


use JGerdes\SchauBot\Entity\Movie;
use JGerdes\SchauBot\Entity\Screening;

class Crawler {

    private $config;
    private $entityManager;

    function __construct($config, $entityManager, $parser) {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->parser = $parser;
    }

    public function crawl() {
        $rawData = $this->fetchRawData();
        $this->parser->setRawData($rawData);
        $this->parser->parse();
        $movies = $this->parser->getMovies();
        $screenings = $this->parser->getScreenings();
        $this->persist($movies, $screenings);
    }

    private function fetchRawData() {
        return file_get_contents($this->config['crawler']['url']);
    }

    /**
     * @param Movie $movies
     * @param Screening $screenings
     */
    private function persist($movies, $screenings) {
        foreach ($movies as $movie) {
            $existing = $this->entityManager
                ->getRepository('JGerdes\SchauBot\Entity\Movie')
                ->findOneBy(array('title' => $movie->getTitle()));
            if (!$existing) {
                $this->entityManager->persist($movie);
                $this->entityManager->flush();
            } else {
                //update all screenings with ne movie id
                //todo: improve this process
                foreach ($screenings as $screening) {
                    if ($screening->getMovie()->getTitle() == $existing->getTitle()) {
                        $screening->setMovie($existing);
                    }
                }
            }
        }

        foreach ($screenings as $screening) {
            $existing = $this->entityManager
                ->getRepository('JGerdes\SchauBot\Entity\Screening')
                ->findOneBy(array(
                    'movie' => $screening->getMovie(),
                    'time' => $screening->getTime()
                ));
            if (!$existing) {
                $this->entityManager->persist($screening);
                $this->entityManager->flush();
            }
        }
    }
}


?>