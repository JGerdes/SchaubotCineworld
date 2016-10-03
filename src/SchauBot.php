<?php

namespace JGerdes\SchauBot;

use Telegram\Bot\Api;
use Katzgrau\KLogger\Logger;
use JGerdes\SchauBot\MessagePrinter;

class SchauBot {

	private $config;
	private $logger;
	private $entityManager;
	private $messagePrinter;
	private $telegram;

	function __construct($config, $entityManager) {
		$this->config = $config;
		$this->entityManager = $entityManager;
		$this->logger = new Logger(LOG_DIR);
		$this->messagePrinter = new MessagePrinter();
	}

	public function run() {

		$this->logger->info('run bot');
		$this->telegram = new Api($this->config['telegram']['token']);

		$updates = $this->telegram->getWebhookUpdates();
		
		if($updates->getMessage() != null) {
			$this->handleTextMessage($updates);
		}
	}

	public function handleTextMessage($update) {
		$chatId = $update->getMessage()->getChat()->getId();
		$movie = $this->entityManager->find('JGerdes\SchauBot\Entity\Movie', 1);
		$text = $this->messagePrinter->generateMovieText($movie);
		$response = $this->telegram->sendMessage([
			'chat_id' => $chatId, 
			'text' => $text,
			'parse_mode' => 'HTML'
		]);
	}
}

?>