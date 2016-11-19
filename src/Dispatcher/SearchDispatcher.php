<?php

namespace JGerdes\SchauBot\Dispatcher;


use JGerdes\SchauBot\MessagePrinter;

class SearchDispatcher extends TextMessageDispatcher {

    /**
     * @param $input string
     * @return string result/answer to given input
     */
    public function getResponse($input) {
        $movie = $this->db->searchMovieByTitle($input);
        $screenings = $this->db->findScreeningsByMovie($movie);

        $printer = new MessagePrinter();
        return $printer->generateMovieText($movie, $screenings, $input);
    }
}