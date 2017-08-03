<?php
/**
 * This class carries ioc initialization functionality used by this component.
 */
declare (strict_types=1);

namespace Maleficarum\Redis\Initializer;

class Initializer {
    /* ------------------------------------ Class Methods START ---------------------------------------- */

    /**
     * This method will initialize the entire package.
     *
     * @param array $opts
     *
     * @return string
     */
    static public function initialize(array $opts = []): string {
        // load default builder if skip not requested
        $builders = $opts['builders'] ?? [];
        is_array($builders) or $builders = [];
        if (!isset($builders['redis']['skip'])) {
            \Maleficarum\Ioc\Container::register('Maleficarum\Redis\Connection\Connection', function ($dep) {
                if (!array_key_exists('Maleficarum\Config', $dep) || !isset($dep['Maleficarum\Config']['redis'])) {
                    throw new \RuntimeException('Impossible to create a \Maleficarum\Redis\Connection\Connection object - no redis config found. \Maleficarum\Ioc\Container::get()');
                }

                return (new \Maleficarum\Redis\Connection\Connection(
                    \Maleficarum\Ioc\Container::get('Redis'),
                    $dep['Maleficarum\Config']['redis']['host'],
                    (int)$dep['Maleficarum\Config']['redis']['port'],
                    $dep['Maleficarum\Config']['redis']['auth']
                ));
            });
        }

        \Maleficarum\Ioc\Container::registerDependency('Maleficarum\Redis', \Maleficarum\Ioc\Container::get('Maleficarum\Redis\Connection\Connection'));

        // return initializer name
        return __METHOD__;
    }

    /* ------------------------------------ Class Methods END ------------------------------------------ */
}
