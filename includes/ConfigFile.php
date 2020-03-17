<?php

namespace WP_Forge\Config;

use InvalidArgumentException;
use RuntimeException;
use WP_Forge\Config\Contracts\ConfigStrategy;
use WP_Forge\Config\Factory\ConfigStrategyFactory;
use WP_Forge\DataStore\DataStore;

/**
 * Class ConfigFile
 *
 * @package WP_Forge\Config
 */
class ConfigFile extends DataStore {

	/**
	 * Full path to the config file.
	 *
	 * @var string
	 */
	protected $file;

	/**
	 * Directory path to the config file.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * The strategy to use for transforming config data.
	 *
	 * @var ConfigStrategy
	 */
	protected $strategy;

	/**
	 * Create a new instance of this class.
	 *
	 * @param string $file Full path to the config file.
	 *
	 * @return static
	 */
	public static function make( $file ) {
		return new static( $file );
	}

	/**
	 * ConfigFile constructor.
	 *
	 * @param string $file Full path to the config file.
	 */
	public function __construct( $file ) {
		$this->file     = $file;
		$this->path     = dirname( $file );
		$this->strategy = ConfigStrategyFactory::create( $file );
	}

	/**
	 * Check if the file exists.
	 *
	 * @return bool
	 */
	public function exists() {
		return file_exists( $this->file );
	}

	/**
	 * Check if the file is readable.
	 *
	 * @return bool
	 */
	public function isReadable() {
		return is_readable( $this->file );
	}

	/**
	 * Check if the file is writable.
	 *
	 * @return bool
	 */
	public function isWritable() {
		return is_writable( $this->file );
	}

	/**
	 * Create the file.
	 *
	 * @return $this
	 */
	public function create() {

		// Create any missing directories
		if ( ! is_dir( $this->path ) ) {
			if ( ! mkdir( $this->path, 0755, true ) ) {
				throw new RuntimeException( sprintf( 'Unable to create directory: "%s"', $this->path ) );
			}
		}

		// Create file if it doesn't exist
		if ( ! $this->exists() ) {
			if ( ! touch( $this->file ) ) {
				throw new RuntimeException( sprintf( 'Unable to create file: "%s"', $this->file ) );
			}
		}

		// Write the current config to file.
		$this->update();

		return $this;
	}

	/**
	 * Read the file. Loads content into the config data store.
	 *
	 * @return $this
	 */
	public function read() {
		if (
			! $this->exists() ||
			! $this->isReadable() ||
			! boolval( $contents = file_get_contents( $this->file ) )
		) {
			throw new RuntimeException( sprintf( 'Unable to read file: "%s"', $this->file ) );
		}

		$this->data = $this->strategy->parse( $contents );

		return $this;
	}

	/**
	 * Write the in-memory config data to the file.
	 *
	 * @return $this
	 */
	public function update() {
		if (
			! $this->exists() ||
			! $this->isWritable() ||
			! boolval( file_put_contents( $this->file, $this->strategy->prepare( $this->data ) . PHP_EOL ) )
		) {
			throw new RuntimeException( sprintf( 'Unable to write to file: "%s"', $this->file ) );
		}

		return $this;
	}

	/**
	 * Delete the file.
	 *
	 * @return $this
	 */
	public function delete() {
		if ( $this->exists() ) {
			if ( ! unlink( $this->file ) ) {
				throw new RuntimeException( sprintf( 'Unable to delete file: "%s"', $this->file ) );
			};
		}

		return $this;
	}

	/**
	 * Magic method to provide read-only access to protected class properties.
	 *
	 * @param string $property
	 *
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __get( $property ) {
		$method = "_{$property}";
		if ( ! property_exists( $this, $property ) || ! method_exists( $this, $method ) ) {
			throw new InvalidArgumentException( sprintf( 'Property %s does not exist', $property ) );
		}

		return $this->{$method}();
	}

	/**
	 * Get full file path to config file.
	 *
	 * @return string
	 */
	protected function _file() {
		return $this->file;
	}

	/**
	 * Directory path to the config file.
	 *
	 * @return string
	 */
	protected function _path() {
		return $this->path;
	}

}
