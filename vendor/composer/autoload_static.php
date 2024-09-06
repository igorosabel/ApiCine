<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf8677434e3395f90126b782be0a0f979
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Osumi\\OsumiFramework\\Plugins\\' => 29,
            'Osumi\\OsumiFramework\\App\\' => 25,
            'Osumi\\OsumiFramework\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Osumi\\OsumiFramework\\Plugins\\' => 
        array (
            0 => __DIR__ . '/..' . '/osumionline/plugin-image/src',
            1 => __DIR__ . '/..' . '/osumionline/plugin-token/src',
        ),
        'Osumi\\OsumiFramework\\App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Osumi\\OsumiFramework\\' => 
        array (
            0 => __DIR__ . '/..' . '/osumionline/framework/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf8677434e3395f90126b782be0a0f979::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf8677434e3395f90126b782be0a0f979::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf8677434e3395f90126b782be0a0f979::$classMap;

        }, null, ClassLoader::class);
    }
}
