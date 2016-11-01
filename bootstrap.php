<?php
require_once "vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


$config = parse_ini_file('config.ini', true);

$paths = array(__DIR__ . "/src");
$isDevMode = ($config['bot']['devMode'] == 'true');

$dbParams = array(
    'driver' => $config['db']['driver'],
    'user' => $config['db']['user'],
    'password' => $config['db']['password'],
    'dbname' => $config['db']['dbname'],
);

$dbconfig = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$dbconfig->setAutoGenerateProxyClasses(Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_ALWAYS);
$entityManager = EntityManager::create($dbParams, $dbconfig);

?>