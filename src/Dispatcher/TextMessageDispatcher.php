<?php

namespace JGerdes\SchauBot\Dispatcher;


abstract class TextMessageDispatcher extends InputDispatcher {


    public function handle($message) {
        $response = $this->getResponse($message->getText());
        if ($response !== null) {
            $this->sendText($message, $response);
        }
        return $response !== null;
    }

    /**
     * @param $input string
     * @return string result/answer to given input
     */
    public abstract function getResponse($input);
}