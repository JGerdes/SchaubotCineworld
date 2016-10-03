<?php

namespace JGerdes\Schaubot\Crawler;

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
		$movies = $this->parser->parseMovies();

		var_dump($movies);
		$this->persist($movies);
	}

	private function fetchRawData() {
		return file_get_contents($this->config['crawler']['url']);
	}

	private function persist($entities) {
		foreach ($entities as $entity) {
			$exists = $user = $this->entityManager
				->getRepository('JGerdes\SchauBot\Entity\Movie')
				->findOneBy(array('title' => $entity->getTitle()));
			if(!$exists) {
				$this->entityManager->persist($entity);
				$this->entityManager->flush();
				$this->entityManager->clear();	
			}
		}
	}
}


?>