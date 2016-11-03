<?php

namespace JGerdes\SchauBot\Dispatcher;

class CommandDispatcher extends InputDispatcher {

    /**
     * @param string $input
     * @return string result/answer to given input
     */
    public function handle($input) {
        switch ($input) {
            case '/start':
                return $this->showWelcomeMessage();
        }
        if ($input[0] === "/") {
            return 'Das war leider kein gültiger Befehl. Frag mich doch einfach was!';
        }
        return null;
    }

    private function showWelcomeMessage() {
        return 'Frag mich nach einen Film, oder einer Vorstellung!';
    }
}