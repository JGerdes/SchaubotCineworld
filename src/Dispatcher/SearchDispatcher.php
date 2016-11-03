<?php

namespace JGerdes\SchauBot\Dispatcher;


use JGerdes\SchauBot\MessagePrinter;

class SearchDispatcher extends InputDispatcher {

    /**
     * @param string $input
     * @return string result/answer to given input
     */
    public function handle($input) {
        $movie = $this->db->searchMovieByTitle($input);
        $screenings = $this->db->findScreeningsByMovie($movie);

        $printer = new MessagePrinter();
        return $printer->generateMovieText($movie, $screenings, $input);
    }
}