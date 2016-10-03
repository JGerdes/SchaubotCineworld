<?php

namespace JGerdes\SchauBot;

use Telegram\Bot\Api;
use Katzgrau\KLogger\Logger;

class SchauBot {

	private $config;
	private $logger;

	function __construct($config, $entityManager) {
		$this->config = $config;
		$this->entityManager = $entityManager;
		$this->logger = new Logger(LOG_DIR);
	}

	public function run() {

		$this->logger->info('run bot');
	    $telegram = new Api($this->config['telegram']['token']);

	    $updates = $telegram->getWebhookUpdates();
	    $movie = $this->entityManager->find('JGerdes\SchauBot\Entity\Movie', 1);
	    if($updates->getMessage() != null) {
		    $chatId = $updates->getMessage()->getChat()->getId();
		    $response = $telegram->sendMessage([
		    	'chat_id' => $chatId, 
		    	'text' => $movie->getTitle()
			]);
		}

	}
}

?>