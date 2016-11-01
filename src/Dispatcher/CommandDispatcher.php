<?php

namespace JGerdes\SchauBot\Dispatcher;

class CommandDispatcher extends InputDispatcher {

    /**
     * @param string $input
     * @return bool whether class can process given input
     */
    public function canHandle($input) {
        return $input[0] === '/';
    }

    /**
     * @param string $input
     * @return string result/answer to given input
     */
    public function handle($input) {
        switch ($input) {
            case '/start':
                return $this->showWelcomeMessage();
        }
        return 'Das war leider kein gültiger Befehl. Frag mich doch einfach was!';
    }

    private function showWelcomeMessage() {
        return 'Frag mich nach einen Film, oder einer Vorstellung!';
    }
}