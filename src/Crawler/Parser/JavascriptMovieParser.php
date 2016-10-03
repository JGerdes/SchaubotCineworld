<?php

namespace JGerdes\Schaubot\Crawler\Parser;

class JavascriptMovieParser {

	const REGEX_PREFIX = "new movie\(";
	const REGEX_ID = "'(\w\w\d\d?)'";
	const REGEX_TITLE = "'(.*?)'";
	const REGEX_RELEASE_DATE = "'(\d{10})'";
	const REGEX_DURATION = "'(\d{2,3})'";
	const REGEX_CONTENT_RATING = "'(\d\d?)'";
	const REGEX_DESCRIPTION = "'((?>\s|\S)*?)'";
	const REGEX_IS_3D = "'(1?)'";
	const REGEX_STUB = "'.*?'";

	const START_STRING_MOVIES = "movies[0]";
	const START_STRING_SCREENINGS = "timetable[0]";

	private $data;
	private $rawMovieData;
	private $rawScreeningData;

	/**
	 * Split data in parts for movie and screening definition
	 */
    private function preprocess($data) {
    	$movieStart = strpos($this->data, SELF::START_STRING_MOVIES);
		$moviePart = substr($this->data, $movieStart, sizeof($this->data) - $movieStart);
		
		$screeningStart = strpos($moviePart, SELF::START_STRING_SCREENINGS);

		$this->rawMovieData = substr($moviePart, 0, $screeningStart);

    	return $data;
    }

	public function parseMovies() {
		$movies = array();
		//put each movie defintion as own string in array
		$rawMovies = explode('movies[', $this->rawMovieData);

		//parse every definition via regex
		$pattern = $this->getMovieRegexPattern();
		foreach ($rawMovies as $rawMovie) {
			if(strlen($rawMovie) > 1) {
				$movies[] = $this->parseSingleMovie($rawMovie, $pattern);
			}
		}
		return $movies;
	}

	public function parseScreenings() {

	}

	public function setRawData($data) {
    	$preprocessed = $this->preprocess($data);
    	$this->data = $preprocessed;
    }

    private function parseSingleMovie($rawMovie, $pattern) {
    	$matches = null;
    	preg_match_all($pattern, $rawMovie, $matches);
    	//todo: create actual movie instance
    }

    private function getMovieRegexPattern() {
    	return '/'
    		.SELF::REGEX_PREFIX
    		.SELF::REGEX_ID.','
    		.SELF::REGEX_TITLE.','
    		.SELF::REGEX_RELEASE_DATE.','
    		.SELF::REGEX_DURATION.','
    		.SELF::REGEX_CONTENT_RATING.','
    		.SELF::REGEX_DESCRIPTION.','
    		.SELF::REGEX_STUB.','
    		.SELF::REGEX_STUB.','
    		.SELF::REGEX_IS_3D.','
    		.'/';
    }

}

?>