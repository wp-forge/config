<?php

namespace WP_Forge\Config\Factory;

use RuntimeException;
use WP_Forge\Config\Contracts\ConfigStrategy;
use WP_Forge\Config\Strategy\JsonStrategy;
use WP_Forge\Config\Strategy\YamlStrategy;

/**
 * Class ConfigStrategyFactory
 *
 * @package WP_Forge\Config\Factory
 */
class ConfigStrategyFactory {

	/**
	 * Create a new config strategy instance based on the provided file name's extension.
	 *
	 * @param string $file The file name.
	 *
	 * @return ConfigStrategy
	 */
	public static function create( $file ) {
		$ext = pathinfo( $file, PATHINFO_EXTENSION );

		switch ( $ext ) {
			case 'json':
				return new JsonStrategy();
			case 'yml':
			case 'yaml':
				return new YamlStrategy();
			default:
				throw new RuntimeException( sprintf( 'Unable to determine config strategy for ".%s" file types.', $ext ) );
		}

	}

}
