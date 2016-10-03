<?php

namespace JGerdes\Schaubot\Crawler;

class Crawler {

	private $config;
	private $entityManager;

	function __construct($config, $entityManager) {
		$this->config = $config;
		$this->entityManager = $entityManager;
	}

	public function crawl() {
		$rawData = $this->fetchRawData();
		//TODO: parse, write to db etc
	}

	private function fetchRawData() {
		return file_get_contents($this->config['crawler']['url']);
	}
}


?>