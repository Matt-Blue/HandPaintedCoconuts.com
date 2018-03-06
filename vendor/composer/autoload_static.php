<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2c4ea460e99d7eec54aa782824482b1d
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static $prefixesPsr0 = array (
        'E' => 
        array (
            'EasyPost' => 
            array (
                0 => __DIR__ . '/..' . '/easypost/easypost-php/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2c4ea460e99d7eec54aa782824482b1d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2c4ea460e99d7eec54aa782824482b1d::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit2c4ea460e99d7eec54aa782824482b1d::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
