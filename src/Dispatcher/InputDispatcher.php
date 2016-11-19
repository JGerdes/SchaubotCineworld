<?php

namespace JGerdes\SchauBot\Dispatcher;


use JGerdes\SchauBot\Database\DbController;
use Katzgrau\KLogger\Logger;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;


abstract class InputDispatcher {

    /**
     * @var DbController
     */
    protected $db;

    /**
     * @var Api
     */
    protected $telegram;

    /**
     * InputDispatcher constructor.
     * @param $dbController DbController
     * @param $telegram Api
     */
    public function __construct($dbController, $telegram) {
        $this->db = $dbController;
        $this->telegram = $telegram;
    }

    /**
     * @param $message Message
     * @param $text string
     */
    protected function sendText($message, $text) {
        $chatId = $message->getChat()->getId();
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML'
        ]);
    }

    /**
     * @param $message Message
     * @param $document string
     */
    protected function sendDocument($message, $document) {
        $chatId = $message->getChat()->getId();
        $this->telegram->sendDocument([
            'chat_id' => $chatId,
            'document' => $document
        ]);
    }

    /**
     * @param $message Message
     * @param $voice string
     * @param $duration int
     */
    protected function sendVoice($message, $voice, $duration) {
        $chatId = $message->getChat()->getId();
        $this->telegram->sendVoice([
            'chat_id' => $chatId,
            'voice' => $voice,
            'duration' => $duration

        ]);
    }

    /**
     * @param $message Message
     */
    protected function startTyping($message) {
        $chatId = $message->getChat()->getId();
        $this->telegram->sendChatAction([
            'chat_id' => $chatId,
            'action' => 'typing'
        ]);
    }

    /**
     * @param $message Message
     */
    protected function startRecord($message) {
        $chatId = $message->getChat()->getId();
        $this->telegram->sendChatAction([
            'chat_id' => $chatId,
            'action' => 'record_audio'
        ]);
    }


    /**
     * @param $message Message
     * @return bool whether message was handled
     */
    public abstract function handle($message);
}