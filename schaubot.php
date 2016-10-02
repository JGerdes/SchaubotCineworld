<?php
    require 'vendor/autoload.php';

    use Telegram\Bot\Api;

    $config = parse_ini_file('config.ini', true);

    $telegram = new Api($config['telegram']['token']);

    
?>