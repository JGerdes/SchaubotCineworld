<?php
require_once "bootstrap.php";

//log errors in file
ini_set('error_log', 'logs/errors.txt');
ini_set('log_errors', true);

define('LOG_DIR', __DIR__.'/logs');


use JGerdes\SchauBot\SchauBot;

$bot = new SchauBot($config, $entityManager);
$bot->run();


?>