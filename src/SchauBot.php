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
	    $text = $this->generateMovieText($movie);
	    if($updates->getMessage() != null) {
		    $chatId = $updates->getMessage()->getChat()->getId();
		    $response = $telegram->sendMessage([
		    	'chat_id' => $chatId, 
		    	'text' => $text,
		    	'parse_mode' => 'HTML'
			]);
		}
	}

	private function generateMovieText($movie) {
		$hourglas = "\xE2\x8C\x9B";
		$desc = $movie->getDescription();
		$desc = str_replace("<br>", "\n", $desc);
		$desc = utf8_encode(strip_tags($desc));
		$text = "<b>".$movie->getTitle()."</b>\n"
			."<i>".$hourglas.$movie->getDuration()." min</i> &#183; ab "
			.$movie->getContentRating()."\n\n"
			.$desc;
		return $text;
	}
}

?>