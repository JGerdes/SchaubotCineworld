<?php
    //log errors in file
    ini_set('error_log', 'logs/errors.txt');
    ini_set('log_errors', true);

    require 'vendor/autoload.php';

    use Katzgrau\KLogger\Logger;
    use Telegram\Bot\Api;

    $logger = new Logger(__DIR__.'/logs');

    $config = parse_ini_file('config.ini', true);

    $telegram = new Api($config['telegram']['token']);


?>