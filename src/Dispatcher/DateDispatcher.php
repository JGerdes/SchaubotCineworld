<?php

namespace JGerdes\SchauBot\Dispatcher;


use JGerdes\SchauBot\MessagePrinter;
use JGerdes\SchauBot\Util as Util;

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
        if (Util::contains($input, 'heute')) {
            return $this->processDay(new \DateTime("today"), "heutige Kinoprogramm");
        }
        if (Util::contains($input, 'morgen')) {
            return $this->processDay(new \DateTime("tomorrow"), "morgige Kinoprogramm");
        }
        $weekday = $this->containsWeekday($input);
        if ($weekday !== null) {
            $writtenDay = ucfirst($weekday['input']);
            return $this->processDay(new \DateTime($weekday['processable']), "Kinoprogramm am " . $writtenDay);
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

    private function containsWeekday($input) {
        foreach ($this->WEEKDAYS as $in => $out) {
            if (Util::contains($input, $in)) {
                return [
                    'input' => $in,
                    'processable' => $out
                ];
            }
        }
        return null;
    }
}