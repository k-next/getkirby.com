<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcc5435cbf327c40cde5b4c79eafdff34
{
    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpDocumentor\\Reflection\\' => 25,
        ),
        'W' => 
        array (
            'Webmozart\\Assert\\' => 17,
        ),
        'K' => 
        array (
            'Kirby\\Site\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpDocumentor\\Reflection\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpdocumentor/reflection-common/src',
            1 => __DIR__ . '/..' . '/phpdocumentor/reflection-docblock/src',
            2 => __DIR__ . '/..' . '/phpdocumentor/type-resolver/src',
        ),
        'Webmozart\\Assert\\' => 
        array (
            0 => __DIR__ . '/..' . '/webmozart/assert/src',
        ),
        'Kirby\\Site\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcc5435cbf327c40cde5b4c79eafdff34::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcc5435cbf327c40cde5b4c79eafdff34::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitcc5435cbf327c40cde5b4c79eafdff34::$classMap;

        }, null, ClassLoader::class);
    }
}
