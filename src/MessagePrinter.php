<?php

namespace JGerdes\SchauBot;


use JGerdes\SchauBot\Entity\Screening;

class MessagePrinter {

    const EMOJI_HOURGLAS = "\xE2\x8C\x9B";
    const EMOJI_CRYING = "\xF0\x9F\x98\xA2";
    const SPECIAL_CHAR_NO_BREAK_SPACE = " ";

    //no const here to be php 5.5 compatible
    private $DESCRIPTION_KEYWORD = [
        "Genre:",
        "Cast:",
        "Darsteller:",
        "Regie:",
        "Laufzeit:",
        "FSK:",
        "Dirigent:"
    ];

    private $WEEKDAYS = [
        "So",
        "Mo",
        "Di",
        "Mi",
        "Do",
        "Fr",
        "Sa"
    ];

    public function generateMovieText($movie, $screenings, $searchQuery = null) {
        if ($movie == null) {
            $searchWrapper = "";
            if ($searchQuery != null) {
                $searchWrapper = "zu <i>" . $searchQuery . "</i>  ";
            }
            return "Entschuldige, ich konnte keinen Film "
            . $searchWrapper
            . "finden " . SELF::EMOJI_CRYING;
        }
        $desc = $this->prettyPrintDescription(
            $movie->getDescription()
        );
        $imageUrl = "http://schauburg-cineworld.de/generated/" . $movie->getOriginalId() . ".jpg";
        $text =
            '<a href="' . $imageUrl . '">' . SELF::SPECIAL_CHAR_NO_BREAK_SPACE . '</a>'
            . "<b>"
            . $movie->getTitle()
            . "</b>\n"
            . "<i>"
            . SELF::EMOJI_HOURGLAS
            . $movie->getDuration()
            . " min</i> &#183; ab "
            . $movie->getContentRating()
            . "\n\n"
            . $desc;

        $table = "";
        if (sizeof($screenings) > 0) {
            $table = "\n\n"
                . "<b>Nächste Spielzeiten:</b>\n"
                . $this->createTimeTable($screenings);
        }

        return $text . $table;
    }

    private function prettyPrintDescription($description) {
        $description = str_replace("<br>", "\n", $description);
        $description = utf8_encode(strip_tags($description));
        foreach ($this->DESCRIPTION_KEYWORD as $keyword) {
            $description = $this->boldify($description, $keyword);
        }
        return $description;
    }

    private function boldify($text, $toBoldify) {
        return str_replace($toBoldify, "<b>" . $toBoldify . "</b>", $text);
    }

    private function createTimeTable($screenings) {
        $table = '';
        foreach ($screenings as $screening) {
            $ticketUrl = 'http://schauburg-cineworld.de/?page_id=6608&showId=' . $screening->getResId();
            $table .=
                $this->WEEKDAYS[$screening->getTime()->format("w")]
                . " "
                . $screening->getTime()->format("H:i")
                . " <i>(Kino "
                . $screening->getHall()
                . "</i>) "
                . ' <a href="' . $ticketUrl . '">[reservieren]</a>'
                . "\n";
        }
        return $table;
    }

    public function generateScreeningOverview($screenings) {
        $data = "";
        /** @var Screening $screening */
        foreach ($screenings as $screening) {
            $data .= "<b>"
                . $screening->getMovie()->getTitle()
                . "</b>: "
                . $screening->getTime()->format("H:i")
                . " <i>(Kino "
                . $screening->getHall()
                . ")</i>\n";
        }
        return utf8_encode($data);
    }
}

?>