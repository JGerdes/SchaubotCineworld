<?php
require_once "bootstrap.php";


use JGerdes\SchauBot\Crawler\Crawler;
use JGerdes\SchauBot\Crawler\Parser\TimeTableParser;

//log errors in file
ini_set('error_log', 'logs/crawler_errors.txt');
ini_set('log_errors', true);

$parser = new TimeTableParser();
$crawler = new Crawler($config, $entityManager, $parser);
$crawler->crawl();


?>