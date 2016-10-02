<?php

namespace JGerdes\SchauBot;

use Telegram\Bot\Api;
use Katzgrau\KLogger\Logger;

class SchauBot {

	private $config;
	private $logger;

	function __construct($config) {
		$this->config = $config;
		$this->logger = new Logger(LOG_DIR);
	}

	public function run() {

		$this->logger->info('run bot');
	    $telegram = new Api($this->config['telegram']['token']);

	    $updates = $telegram->getWebhookUpdates();

	    $chatId = $updates->getMessage()->getChat()->getId();
	    $response = $telegram->sendMessage([
	    	'chat_id' => $chatId, 
	    	'text' => 'Hello World'
		]);
	}
}

?>