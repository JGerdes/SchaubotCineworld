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
		//TODO: write to db etc
	}

	private function fetchRawData() {
		return file_get_contents($this->config['crawler']['url']);
	}
}


?>