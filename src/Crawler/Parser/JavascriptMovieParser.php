<?php

namespace JGerdes\Schaubot\Crawler\Parser;


use JGerdes\SchauBot\Entity\Movie;
use JGerdes\SchauBot\Entity\Screening;


class MovieParser {

	const REGEX_PREFIX = "new movie\(";
	const REGEX_ID = "'(\w\w\d\d?)'";
	const REGEX_TITLE = "'(.*?)'";
	const REGEX_RELEASE_DATE = "'(\d{10})'";
	const REGEX_DURATION = "'(\d{2,3})'";
	const REGEX_CONTENT_RATING = "'(\d\d?)'";
	const REGEX_DESCRIPTION = "'((?>\s|\S)*?)'";
	const REGEX_IS_3D = "'(1?)'";
	const REGEX_STUB = "'.*?'";

	const FORMAT_DATE = "ymdHi";

	
	public function parse($rawMovie) {
		$pattern = $this->getMovieRegexPattern();
		$matches = null;
		preg_match_all($pattern, $rawMovie, $matches);
		$movie = new Movie();
		$movie->setTitle($matches[2][0]);
		$movie->setDuration((int)$matches[4][0]);
		$movie->setContentRating((int)$matches[5][0]);
		$movie->setDescription($matches[6][0]);
		$movie->set3D($matches[7][0] === '1');
		$date = \DateTime::createFromFormat(SELF::FORMAT_DATE, $matches[3][0]);
		$movie->setReleaseDate($date);
		return $movie;
		
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

class ScreeningParser {

	const REGEX_PREFIX = "new seance\(";
	const REGEX_MOVIE_ID = "'(\d{1,3})'";
	const REGEX_TIME = "'(\d{10})'";
	const REGEX_RES_ID = "'(\d+?)'";
	const REGEX_HALL = "'(\d)'";
	const REGEX_STUB = "'.*?'";

	const FORMAT_DATE = "ymdHi";

	
	public function parse($rawScreening, $movies) {
		$pattern = $this->getScreeningRegexPattern();
		$matches = null;
		preg_match_all($pattern, $rawScreening, $matches);
		$time = \DateTime::createFromFormat(SELF::FORMAT_DATE, $matches[2][0]);
		$screening = new Screening();
		$screening->setTime($time);
		$screening->setResId($matches[3][0]);
		$screening->setHall($matches[4][0]);
		$screening->setMovie($movies[$matches[1][0]]);
		return $screening;
		
	}

	private function getScreeningRegexPattern() {
		return '/'
			.SELF::REGEX_PREFIX
			.SELF::REGEX_MOVIE_ID.','
			.SELF::REGEX_TIME.','
			.SELF::REGEX_RES_ID.','
			.SELF::REGEX_STUB.','
			.SELF::REGEX_HALL.'\);'
			.'/';
	}
}


class JavascriptMovieParser {

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

		$movieParser = new \JGerdes\Schaubot\Crawler\Parser\MovieParser();
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