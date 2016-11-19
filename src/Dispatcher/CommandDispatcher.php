<?php

namespace JGerdes\SchauBot\Dispatcher;

use JGerdes\SchauBot\Emoji;

class CommandDispatcher extends InputDispatcher {

    /**
     * @param string $input
     * @return string result/answer to given input
     */
    public function handle($input) {
        switch ($input) {
            case '/start':
            case '/help':
            case '/hilfe':
            case 'hilfe':
            case 'Hilfe':
                return $this->showHelp();
        }
        if ($input[0] === "/") {
            return 'Das war leider kein gültiger Befehl' . Emoji::CONFUSED . ' Frag mich doch einfach was!';
        }
        return null;
    }

    private function showHelp() {
        return ""
        . "Hey!\n"
        . "Du kannst mich einfach irgendwas fragen, was Dich interessiert."
        . " Ich versuche dann so gut es geht zu antworten.\n"
        . "Probiere zum Beispiel mal folgende Fragen:\n\n"
        . Emoji::MIDDLE_DOT . " <i>Was läuft heute?</i>\n"
        . Emoji::MIDDLE_DOT . " <i>Welche Filme werden am Wochenende gezeigt?</i>\n"
        . Emoji::MIDDLE_DOT . " <i>Und am Mittwoch?</i>\n"
        . "\nDu kannst aber auch gezielt nach Filmen suchen."
        . " Gibt dafür einfach ohne irgendwas anderes Teile des Names ein, ich suche dann für Dich was passendes!\n"
        . "\n Na dann mal <i>Action!</i>  " . Emoji::CLAPPER_BOARD;
    }
}