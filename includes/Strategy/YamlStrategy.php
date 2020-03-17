<?php

namespace WP_Forge\Config\Strategy;

use Mustangostang\Spyc;
use WP_Forge\Config\Contracts\ConfigStrategy;

/**
 * Class YamlStrategy
 *
 * @package WP_Forge\Config\Strategy
 */
class YamlStrategy implements ConfigStrategy {

	/**
	 * Parse raw file contents into a data array.
	 *
	 * @param $contents
	 *
	 * @return mixed
	 */
	function parse( $contents ) {
		return Spyc::YAMLLoadString( $contents );
	}

	/**
	 * Parse a data array into raw file contents.
	 *
	 * @param $data
	 *
	 * @return false|string
	 */
	function prepare( $data ) {
		return Spyc::YAMLDump( $data );
	}

}
