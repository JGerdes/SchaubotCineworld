<?php
    require 'vendor/autoload.php';

    use Katzgrau\KLogger\Logger;
    use Telegram\Bot\Api;

    $logger = new Logger(__DIR__.'/logs');

    $config = parse_ini_file('config.ini', true);

    $telegram = new Api($config['telegram']['token']);

    

?>