<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4db8e727818a6fd9d9d1ecaf08e3d793
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit4db8e727818a6fd9d9d1ecaf08e3d793::$classMap;

        }, null, ClassLoader::class);
    }
}
