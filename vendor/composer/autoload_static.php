<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf1c090513655da23d56d96bb11545e8d
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\CssSelector\\' => 30,
        ),
        'F' => 
        array (
            'FastSimpleHTMLDom\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\CssSelector\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/css-selector',
        ),
        'FastSimpleHTMLDom\\' => 
        array (
            0 => __DIR__ . '/..' . '/dimabdc/php-fast-simple-html-dom-parser/lib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf1c090513655da23d56d96bb11545e8d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf1c090513655da23d56d96bb11545e8d::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
