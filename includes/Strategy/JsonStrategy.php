<?php

namespace WP_Forge\Config\Strategy;

use RuntimeException;
use WP_Forge\Config\Contracts\ConfigStrategy;

/**
 * Class JsonStrategy
 *
 * @package WP_Forge\Config\Strategy
 */
class JsonStrategy implements ConfigStrategy {

	/**
	 * Parse raw file contents into a data array.
	 *
	 * @param $contents
	 *
	 * @return mixed
	 */
	function parse( $contents ) {
		$data = json_decode( $contents, true );
		if ( is_null( $data ) ) {
			throw new RuntimeException( 'Unable to JSON decode data!' );
		}

		return $data;
	}

	/**
	 * Parse a data array into raw file contents.
	 *
	 * @param $data
	 *
	 * @return false|string
	 */
	function prepare( $data ) {
		$contents = json_encode( (object) $data, JSON_PRETTY_PRINT );
		if ( ! $contents ) {
			throw new RuntimeException( 'Unable to JSON encode data!' );
		}

		return $contents;
	}

}
