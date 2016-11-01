<?php

namespace JGerdes\SchauBot;

use JGerdes\SchauBot\Database\DbController;
use JGerdes\SchauBot\Dispatcher\CommandDispatcher;
use JGerdes\SchauBot\Dispatcher\InputDispatcher;
use Katzgrau\KLogger\Logger;
use Telegram\Bot\Api;

class SchauBot {

    private $config;
    private $logger;
    private $entityManager;
    private $messagePrinter;
    private $telegram;
    /**
     * @var InputDispatcher[]
     */
    private $inputDispatcher;

    function __construct($config, $entityManager) {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->logger = new Logger(LOG_DIR);
        $this->messagePrinter = new MessagePrinter();

        $db = new DbController($entityManager);

        $this->inputDispatcher = [
            new CommandDispatcher($db)
        ];
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

        $text = null;
        foreach ($this->inputDispatcher as $dispatcher) {
            if ($dispatcher->canHandle($query)) {
                $text = $dispatcher->handle($query);
            }
        }
        if ($text === null) {
            if (strpos($query, 'heute') !== false) {
                $screenings = $this->findScreenings(new \DateTime('today'));
                $text = $this->messagePrinter->generateScreeningOverview($screenings);
            } else {
                $movie = $this->searchMovie($query);
                $times = $this->findTimesByMovie($movie);
                $text = $this->messagePrinter->generateMovieText($movie, $times, $query);
            }
        }
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

    private function findTimesByMovie($movie) {
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

    private function findScreenings($date) {
        $from = new \DateTime($date->format("Y-m-d") . " 00:00:00");
        $to = new \DateTime($date->format("Y-m-d") . " 23:59:59");

        return $this->entityManager
            ->getRepository('JGerdes\SchauBot\Entity\Screening')
            ->createQueryBuilder("e")
            ->where('e.time BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()->getResult();
    }
}

?>