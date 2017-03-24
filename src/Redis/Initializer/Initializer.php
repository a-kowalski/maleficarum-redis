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
	 * @return string
	 */
	static public function initialize(array $opts = []) : string {
		// load default builder if skip not requested
		$builders = $opts['builders'] ?? [];
		is_array($builders) or $builders = [];
		if (!isset($builders['redis']['skip'])) {
			\Maleficarum\Ioc\Container::register('Maleficarum\Redis\Connection\Connection', function ($dep, $opt) {

			});
		}

		\Maleficarum\Ioc\Container::registerDependency('Maleficarum\Redis', \Maleficarum\Ioc\Container::get('Maleficarum\Redis\Connection\Connection'));

		// return initializer name
		return __METHOD__;
	}

	/* ------------------------------------ Class Methods END ------------------------------------------ */

}