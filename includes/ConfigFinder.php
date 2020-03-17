<?php

namespace WP_Forge\Config;

use RuntimeException;

/**
 * Class ConfigFinder
 *
 * @package WP_Forge\Config
 */
class ConfigFinder {

	/**
	 * The config file names.
	 *
	 * @var array
	 */
	protected $names;

	/**
	 * Whether or not to traverse up the directory tree.
	 *
	 * @var bool
	 */
	protected $traverse = true;

	/**
	 * Returns a new instance.
	 *
	 * @param string|array $names The name(s) of the config file(s). Checked in order.
	 *
	 * @return ConfigFinder
	 */
	public static function searchFor( $names ) {
		return new self( $names );
	}

	/**
	 * ConfigFinder constructor.
	 *
	 * @param string|array $names The name(s) of the config file(s). Checked in order.
	 */
	public function __construct( $names ) {
		$this->names = is_array( $names ) ? $names : [ (string) $names ];
	}

	/**
	 * Change whether or not to traverse up the directory tree.
	 *
	 * @param $bool
	 *
	 * @return $this
	 */
	public function shouldTraverse( $bool ) {
		$this->traverse = boolval( $bool );

		return $this;
	}

	/**
	 * Find a config file, if one exists.
	 *
	 * @param string $path The directory from which to start looking.
	 *
	 * @return string|null The file path on success or null on failure.
	 */
	public function find( $path ) {
		$found = null;

		while ( is_readable( $path ) && is_null( $found ) && $path !== dirname( $this->getHomeDir() ) ) {
			foreach ( $this->names as $name ) {
				$file = $path . DIRECTORY_SEPARATOR . $name;
				if ( file_exists( $file ) && is_readable( $file ) ) {
					$found = $file;
					break;
				}
			}
			if ( ! $this->traverse ) {
				break;
			}
			$path = dirname( $path );
		}

		return $found;
	}

	/**
	 * Find a config file, if one exists. Otherwise, throw an exception.
	 *
	 * @param string $path The directory from which to start looking.
	 *
	 * @return string The file path.
	 *
	 * @throws RuntimeException
	 */
	public function find_or_die( $path ) {
		$found = $this->find( $path );
		if ( is_null( $this->find( $path ) ) ) {
			throw new RuntimeException( 'No config file found!' );
		}

		return $found;
	}

	/**
	 * Get the user's home directory.
	 *
	 * @return string
	 */
	protected function getHomeDir() {
		$home = getenv( 'HOME' );
		if ( ! $home ) {
			// In Windows $HOME may not be defined
			$home = getenv( 'HOMEDRIVE' ) . getenv( 'HOMEPATH' );
		}

		return rtrim( $home, '/\\' );
	}

}
