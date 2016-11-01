<?php

namespace JGerdes\SchauBot\Crawler\Parser;


class TimeTableParser {

	const START_STRING_MOVIES = "movies[0]";
	const START_STRING_SCREENINGS = "timetable[0]";

	private $data;
	private $rawMovieData;
	private $rawScreeningData;
	private $movies = null;
	private $screenings = null;


	public function setRawData($data) {
		$preprocessed = $this->preprocess($data);
		$this->data = $preprocessed;
	}

	public function parse() {
		$this->parseMovies();
		$this->parseScreenings();
	}

	public function getMovies() {
		return $this->movies;
	}

	public function getScreenings() {
		return $this->screenings;
	}

	/**
	 * Split data in parts for movie and screening definition
	 */
	private function preprocess($data) {
		$movieStart = strpos($data, SELF::START_STRING_MOVIES);
		$moviePart = substr($data, $movieStart, strlen($data) - $movieStart);

		$screeningStart = strpos($moviePart, SELF::START_STRING_SCREENINGS);

		$this->rawMovieData = substr($moviePart, 0, $screeningStart);
		$this->rawScreeningData = substr($moviePart, $screeningStart, strlen($moviePart) - $screeningStart);
		return $data;
	}

	private function parseMovies() {
		$movies = array();
		//put each movie defintion as own string in array
		$rawMovies = explode('movies[', $this->rawMovieData);

		$movieParser = new MovieParser();
		foreach ($rawMovies as $rawMovie) {
			if(strlen($rawMovie) > 1) {
				$movies[] = $movieParser->parse($rawMovie);
			}
		}
		$this->movies = $movies;
	}

	private function parseScreenings() {
		$screenings = array();

		$rawScreenings = explode('timetable[', $this->rawScreeningData);

		$parser = new ScreeningParser();
		foreach ($rawScreenings as $rawScreening) {
			if(strlen($rawScreening) > 1) {
				$screenings[] = $parser->parse($rawScreening, $this->movies);
			}
		}
		$this->screenings = $screenings;
	}

}

?>