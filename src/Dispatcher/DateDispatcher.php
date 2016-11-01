<?php

namespace JGerdes\SchauBot\Dispatcher;


use JGerdes\SchauBot\MessagePrinter;

class DateDispatcher extends InputDispatcher {

    /**
     * @param string $input
     * @return bool whether class can process given input
     */
    public function canHandle($input) {
        $input = strtolower($input);
        return $input === 'heute';
    }

    /**
     * @param string $input
     * @return string result/answer to given input
     */
    public function handle($input) {
        $input = strtolower($input);
        switch ($input) {
            case 'heute':
                return $this->processDay(new \DateTime("today"));
            case 'morgen':
                return $this->processDay(new \DateTime("tomorrow"));
        }
        return "Entschuldige, ich konnte für <i>" . $input . "</i> keine Vorstellung finden";
    }

    private function processDay($date) {
        $screenings = $this->db->findScreeningsForDate($date);
        $printer = new MessagePrinter();
        return $printer->generateScreeningOverview($screenings);
    }
}