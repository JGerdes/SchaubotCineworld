<?php
require_once "bootstrap.php";


use JGerdes\SchauBot\Crawler\Crawler;

$crawler = new Crawler($config, $entityManager);
$crawler->crawl();


?>