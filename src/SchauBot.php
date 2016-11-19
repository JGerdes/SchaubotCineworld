<?php

namespace JGerdes\SchauBot;

use JGerdes\SchauBot\Database\DbController;
use JGerdes\SchauBot\Dispatcher\CommandDispatcher;
use JGerdes\SchauBot\Dispatcher\DateDispatcher;
use JGerdes\SchauBot\Dispatcher\InputDispatcher;
use JGerdes\SchauBot\Dispatcher\SearchDispatcher;
use Katzgrau\KLogger\Logger;
use Telegram\Bot\Api;

class SchauBot {

    private $config;
    private $logger;
    private $entityManager;
    private $telegram;
    /**
     * @var InputDispatcher[]
     */
    private $inputDispatcher;

    function __construct($config, $entityManager) {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->logger = new Logger(LOG_DIR);
    }

    public function run() {
        $this->logger->info('run bot');
        $this->telegram = new Api($this->config['telegram']['token']);

        $db = new DbController($this->entityManager);

        $this->inputDispatcher = [
            new CommandDispatcher($db, $this->telegram),
            new DateDispatcher($db, $this->telegram),
            new SearchDispatcher($db, $this->telegram)
        ];

        $updates = $this->telegram->getWebhookUpdates();

        if ($updates->getMessage() != null) {
            $this->sendResponse($updates->getMessage());
        }
    }


    private function sendResponse($message) {
        $response = null;
        foreach ($this->inputDispatcher as $dispatcher) {
            $handled = $dispatcher->handle($message);
            if ($handled === true) {
                return;
            }
        }
        $text = "Ich habe Dich leider nicht verstanden " . Emoji::CRYING;
        $chatId = $message->getChat()->getId();
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML'
        ]);
    }

}

?>