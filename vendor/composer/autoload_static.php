<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite9a52791392e9d196f5a0f8d9913d1a4
{
    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'Zend\\Escaper\\' => 13,
        ),
        'R' => 
        array (
            'RedBeanPHP\\' => 11,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'PhpOffice\\PhpWord\\' => 18,
            'PhpOffice\\Common\\' => 17,
            'ParseCsv\\extensions\\' => 20,
            'ParseCsv\\' => 9,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
        'A' => 
        array (
            'App\\MW\\' => 7,
            'App\\EXC\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Zend\\Escaper\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-escaper/src',
        ),
        'RedBeanPHP\\' => 
        array (
            0 => __DIR__ . '/..' . '/gabordemooij/redbean/RedBeanPHP',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'PhpOffice\\PhpWord\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/phpword/src/PhpWord',
        ),
        'PhpOffice\\Common\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/common/src/Common',
        ),
        'ParseCsv\\extensions\\' => 
        array (
            0 => __DIR__ . '/..' . '/parsecsv/php-parsecsv/src/extensions',
        ),
        'ParseCsv\\' => 
        array (
            0 => __DIR__ . '/..' . '/parsecsv/php-parsecsv/src',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'App\\MW\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/mw',
        ),
        'App\\EXC\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/exc',
        ),
    );

    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Slim' => 
            array (
                0 => __DIR__ . '/..' . '/slim/slim',
            ),
        ),
        'P' => 
        array (
            'PHPExcel' => 
            array (
                0 => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes',
            ),
        ),
        'F' => 
        array (
            'Flynsarmy\\SlimMonolog' => 
            array (
                0 => __DIR__ . '/..' . '/flynsarmy/slim-monolog',
            ),
        ),
    );

    public static $classMap = array (
        'PclZip' => __DIR__ . '/..' . '/pclzip/pclzip/pclzip.lib.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite9a52791392e9d196f5a0f8d9913d1a4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite9a52791392e9d196f5a0f8d9913d1a4::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInite9a52791392e9d196f5a0f8d9913d1a4::$prefixesPsr0;
            $loader->classMap = ComposerStaticInite9a52791392e9d196f5a0f8d9913d1a4::$classMap;

        }, null, ClassLoader::class);
    }
}
