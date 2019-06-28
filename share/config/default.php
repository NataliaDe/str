<?php

$config['db'] = array(
    'driver' => 'mysql',
    //local
//    'host' => 'localhost',
//    'user' => 'ss',
//    'pass' => 'ss02GB47',
//    'dbname' => 'str'

    'host' => '172.26.200.14',
    'user' => 'str_natali',
    'pass' => 'str_natali',
    'dbname' => 'str'


        /*
          'host'		=> 'localhost',
          'user'		=> 'root',
          'pass'		=> '',
          'dbname'	=> 'sakila'
         */
        //172.26.200.14
        /* 'host'		=> '172.26.200.14',
          'user'		=> 'ltt',
          'pass'		=> 'ww01gb47',
          'dbname'	=> 'lestorftrava' */
);

$config['db']['dsn'] = sprintf(
        '%s:host=%s;dbname=%s', $config['db']['driver'], $config['db']['host'], $config['db']['dbname']
);
$config['app']['mode'] = $_ENV['SLIM_MODE'];

$config['app']['cache.ttl'] = 60;

$config['app']['rate.limit'] = 1000;
$config['app']['log.writer'] = new \Flynsarmy\SlimMonolog\Log\MonologWriter(array(
    'handlers' => array(
        new \Monolog\Handler\StreamHandler(//путь для сохранения
                realpath(__DIR__ . '/../logs')
                . '/' . $_ENV['SLIM_MODE'] . '_' . date('Y-m-d') . '.log'
        ),
        new \Monolog\Handler\BrowserConsoleHandler
    ),
//	'formatters' => new \Monolog\Formatter\JsonFormatter,
    'processors' => array(
        new \Monolog\Processor\WebProcessor,
        new \Monolog\Processor\MemoryPeakUsageProcessor,
        new \Monolog\Processor\ProcessIdProcessor,
        new \Monolog\Processor\UidProcessor,
        new \Monolog\Processor\MemoryUsageProcessor
    )
        ));

$config['app']['cookies.encrypt'] = true;
$config['app']['cookies.lifetime'] = '10 minutes';
$config['app']['cookies.path'] = '/';
$config['app']['cookies.domain'] = null;
$config['app']['cookies.secure'] = false;
$config['app']['cookies.httponly'] = true;
$config['app']['cookies.secret_key'] = '05ymStMF2suR';
//$config['app']['cookies.cipher']		= MCRYPT_RIJNDAEL_256;
//$config['app']['cookies.cipher_mode']	= MCRYPT_MODE_CBC;