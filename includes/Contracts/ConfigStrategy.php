<?php

namespace WP_Forge\Config\Contracts;

/**
 * Interface ConfigStrategy
 *
 * @package WP_Forge\Config\Contracts
 */
interface ConfigStrategy {

	/**
	 * Parse the data from file contents.
	 *
	 * @param string $contents The raw file contents.
	 *
	 * @return array The structured data array.
	 */
	public function parse( $contents );

	/**
	 * Prepare the data to be written to a file.
	 *
	 * @param array $data The structured data array.
	 *
	 * @return string The raw file contents.
	 */
	public function prepare( $data );

}
