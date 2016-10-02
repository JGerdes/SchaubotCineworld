<?php
    //log errors in file
    ini_set('error_log', 'logs/errors.txt');
    ini_set('log_errors', true);

    define('LOG_DIR', __DIR__.'/logs');

    require 'vendor/autoload.php';

    use JGerdes\SchauBot\SchauBot;

    $config = parse_ini_file('config.ini', true);

    $bot = new SchauBot($config);
    $bot->run();


?>