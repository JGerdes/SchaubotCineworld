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

        if ($updates->getMessage() != null) {
            $this->handleTextMessage($updates);
        }
    }

    private function handleTextMessage($update) {
        $chatId = $update->getMessage()->getChat()->getId();
        $query = $update->getMessage()->getText();
        $movie = $this->searchMovie($query);
        $times = $this->findTimes($movie);
        $text = $this->messagePrinter->generateMovieText($movie, $times, $query);
        $response = $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML'
        ]);
    }

    private function searchMovie($query) {
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

    private function findTimes($movie) {
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
}

?>