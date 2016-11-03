<?php

namespace JGerdes\SchauBot\Dispatcher;


use JGerdes\SchauBot\MessagePrinter;

class DateDispatcher extends InputDispatcher {

    private $WEEKDAYS = [
        'montag' => 'monday',
        'dienstag' => 'tuesday',
        'mittwoch' => 'wednesday',
        'donnerstag' => 'thursday',
        'freitag' => 'friday',
        'samstag' => 'saturday',
        'sonntag' => 'sunday'
    ];

    /**
     * @param string $input
     * @return string result/answer to given input
     */
    public function handle($input) {
        $input = strtolower($input);
        if ($input == 'heute') {
            return $this->processDay(new \DateTime("today"), "heutige Kinoprogramm");
        }
        if ($input == 'morgen') {
            return $this->processDay(new \DateTime("tomorrow"), "morgige Kinoprogramm");
        }
        if (array_key_exists($input, $this->WEEKDAYS)) {
            $dateDay = $this->WEEKDAYS[$input];
            $writtenDay = ucfirst($input);
            return $this->processDay(new \DateTime($dateDay), "Kinoprogramm am " . $writtenDay);
        }

        return null;
    }

    private function processDay($date, $identifier) {
        $screenings = $this->db->findScreeningsForDate($date);
        if (sizeof($screenings) === 0) {
            return "Entschuldige, ich konnte das " . $identifier . " nicht finden.";
        } else {
            $response = "Hier das " . $identifier . ":";
            $printer = new MessagePrinter();
            $response .= $printer->generateScreeningOverview($screenings);
            return $response;
        }
    }
}