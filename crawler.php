<?php
require_once "bootstrap.php";


use JGerdes\SchauBot\Crawler\Crawler;
use JGerdes\SchauBot\Crawler\Parser\JavascriptMovieParser;

$parser = new JavascriptMovieParser();
$crawler = new Crawler($config, $entityManager, $parser);
$crawler->crawl();


?>