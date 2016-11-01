<?php
require_once "bootstrap.php";


use JGerdes\SchauBot\Crawler\Crawler;
use JGerdes\SchauBot\Crawler\Parser\TimeTableParser;

$parser = new TimeTableParser();
$crawler = new Crawler($config, $entityManager, $parser);
$crawler->crawl();


?>