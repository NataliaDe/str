<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';

date_default_timezone_set('Europe/Minsk');

use \Slim\Slim;
use \RedBeanPHP\Facade as R;

if (empty($_ENV['SLIM_MODE'])) {
    $_ENV['SLIM_MODE'] = (getenv('SLIM_MODE')) ? getenv('SLIM_MODE') : 'development';
}

$config = array();
$configFile = dirname(__FILE__) . '/share/config/' . $_ENV['SLIM_MODE'] . '.php';

if (is_readable($configFile)) {
    require_once $configFile;
} else {
    require_once dirname(__FILE__) . '/share/config/default.php';
}

//$app = new \Slim\Slim($config['app']);
$app = new Slim($config['app']);

// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'log.level' => \Slim\Log::WARN,
        'debug' => false
    ));
});
// Only invoked if mode is "development"
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'log.level' => \Slim\Log::DEBUG,
        'debug' => true
    ));
});

$log = $app->getLog();
R::setup($config['db']['dsn'], $config['db']['user'], $config['db']['pass']);
//R::setup('mysql:host=localhost;dbname=ss', 'ss', 'ss02GB47');
