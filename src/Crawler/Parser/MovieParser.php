<?php

namespace JGerdes\SchauBot\Crawler\Parser;


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
        . SELF::REGEX_PREFIX
        . SELF::REGEX_ID . ','
        . SELF::REGEX_TITLE . ','
        . SELF::REGEX_RELEASE_DATE . ','
        . SELF::REGEX_DURATION . ','
        . SELF::REGEX_CONTENT_RATING . ','
        . SELF::REGEX_DESCRIPTION . ','
        . SELF::REGEX_STUB . ','
        . SELF::REGEX_STUB . ','
        . SELF::REGEX_IS_3D . ','
        . '/';
    }
}

?>