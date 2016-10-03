<?php

namespace JGerdes\Schaubot\Crawler\Parser;

class JavascriptMovieParser {

	private $data;

    private function preprocess($data) {
    	return $data;
    }

	public function parseMovies() {
		$movies = array();
		//Todo: parse
		return $movies;
	}

	public function parseScreenings() {

	}

	public function setRawData($data) {
    	$preprocessed = $this->preprocess($data);
    	$this->data = $preprocessed;
    }

}

?>