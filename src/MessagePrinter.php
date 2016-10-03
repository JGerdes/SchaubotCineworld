<?php

namespace JGerdes\SchauBot;


class MessagePrinter {

	const EMOJI_HOURGLAS = "\xE2\x8C\x9B";
	const EMOJI_CRYING = "\xF0\x9F\x98\xA2";

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

	public function generateMovieText($movie, $searchQuery = null) {
		if($movie == null) {
			$searchWrapper = "";
			if($searchQuery != null) {
				$searchWrapper = "zu <i>".$searchQuery."</i>  ";
			}
			return "Entschuldige, ich konnte keinen Film "
					.$searchWrapper
					."finden ".SELF::EMOJI_CRYING;
		}
		$desc = $this->prettyPrintDescription(
			$movie->getDescription()
		);
		$text = 
			"<b>"
			.$movie->getTitle()
			."</b>\n"
			."<i>"
			.SELF::EMOJI_HOURGLAS
			.$movie->getDuration()
			." min</i> &#183; ab "
			.$movie->getContentRating()
			."\n\n"
			.$desc;
		return $text;
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
		return str_replace($toBoldify, "<b>".$toBoldify."</b>", $text);
	}
}

?>