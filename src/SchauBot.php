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

        $db = new DbController($entityManager);

        $this->inputDispatcher = [
            new CommandDispatcher($db),
            new DateDispatcher($db),
            new SearchDispatcher($db)
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
        $input = $update->getMessage()->getText();

        $text = $this->getResponse($input);

        $response = $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML'
        ]);
    }

    private function getResponse($input) {
        foreach ($this->inputDispatcher as $dispatcher) {
            if ($dispatcher->canHandle($input)) {
                return $dispatcher->handle($input);
            }
        }
        return "Ich habe Dich leider nicht verstanden";
    }

}

?>