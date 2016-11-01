<?php

namespace JGerdes\SchauBot\Crawler\Parser;


use JGerdes\SchauBot\Entity\Movie;
use JGerdes\SchauBot\Entity\Screening;


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
?>