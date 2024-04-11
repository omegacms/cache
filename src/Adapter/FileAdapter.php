<?php
/**
 * Part of Omega CMS - Cache Package
 *
 * @link       https://omegacms.github.io
 * @author     Adriano Giovannini <omegacms@outlook.com>
 * @copyright  Copyright (c) 2022 Adriano Giovannini. (https://omegacms.github.io)
 * @license    https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 */

/**
 * @declare
 */
declare( strict_types = 1 );

/**
 * @namespace
 */
namespace Omega\Cache\Adapter;

/**
 * @use
 */
use function glob;
use function json_decode;
use function json_encode;
use function file_get_contents;
use function file_put_contents;
use function is_int;
use function is_file;
use function sha1;
use function time;
use function unlink;
use Omega\Application\Application;

/**
 * File adapter class.
 *
 * The `FileAdapter` class implements a cache adapter that stores cached data in
 * files. It extends the AbstractCacheAdapter class and provides methods to read,
 * write, and manage cache data stored in files.
 *
 * @category    Omega
 * @package     Omega\Cache
 * @subpackage  Omega\Cache\Adapter
 * @link        https://omegacms.github.io
 * @author      Adriano Giovannini <omegacms@outlook.com>
 * @copyright   Copyright (c) 2022 Adriano Giovannini. (https://omegacms.github.io)
 * @license     https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 * @version     1.0.0
 */
class FileAdapter extends AbstractCacheAdapter
{
    /**
     * FileAdapter class constructor.
     *
     * Initializes the FileAdapter with configuration options.
     *
     * @param  array $config Holds an array of configuration options.
     * @return void
     */
    public function __construct( array $config )
    {
        parent::__construct( $config );
    }

    /**
     * @inheritdoc
     *
     * @param  string $key Holds the cache key to check.
     * @return bool Returns true if the key exists in the cache, otherwise false.
     */
    public function has( string $key ) : bool
    {
        $data = $this->cached[ $key ] = $this->read( $key );

        return isset( $data[ 'expires' ] ) && $data[ 'expires' ] > time();
    }

    /**
     * @inheritdoc
     *
     * @param  string $key     Holds the cache key to retrieve.
     * @param  mixed  $default Holds the default value to return if the key is not found.
     * @return mixed Return the cached value if found, otherwise the default value.
     */
    public function get( string $key, mixed $default = null ) : mixed
    {
        if ( $this->has( $key ) ) {
            return $this->cached[ $key ][ 'value' ];
        }

        return $default;
    }

    /**
     * @inheritdoc
     *
     * @param  string $key     Holds the cache key to store.
     * @param  mixed  $value   Holds the value to store in the cache.
     * @param  ?int   $seconds Holds the number of seconds until the cache item expires (null for no expiration).
     * @return $this Return the cache adapter instance.
     */
    public function put( string $key, mixed $value, ?int $seconds = null ) : static
    {
        if ( ! is_int( $seconds ) ) {
            $seconds = (int)$this->config[ 'seconds' ];
        }

        $data = $this->cached[ $key ] = [
            'value'   => $value,
            'expires' => time() + $seconds
        ];

        return $this->write( $key, $data );
    }

    /**
     * @inheritdoc
     *
     * @param  string $key Holds the cache key to remove.
     * @return $this Return the cache adapter instance.
     */
    public function forget( string $key ) : static
    {
        unset( $this->cached[ $key ] );

        $path = $this->getPath( $key );

        if ( is_file( $path ) ) {
            unlink( $path );
        }

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return $this Return the cache adapter instance.
     */
    public function flush() : static
    {
        $this->cached = [];
        $files        = glob( $this->getBase() . DIRECTORY_SEPARATOR . '*.json' );

        foreach ( $files as $file ) {
            if ( is_file( $file ) ) {
                unlink( $file );
            }
        }

        return $this;
    }

    /**
     * Get the cache path for a specific key.
     *
     * @param  string $key Holds the cache key.
     * @return string Return the cache path for the specified key.
     */
    private function getPath( string $key ) : string
    {
        return $this->getBase() . DIRECTORY_SEPARATOR . sha1( $key ) . '.json';
    }


    /**
     * Get the cache base directory.
     *
     * @return string Return the base directory for storing cache files.
     */
    private function getBase() : string
    {
        return $this->config[ 'path' ];
    }

    /**
     * Read the cache data from a file.
     *
     * @param  string $key Holds the cache key.
     * @return mixed|array Return the cached data, or an empty array if not found.
     */
    private function read( string $key ) : mixed
    {
        $path = $this->getPath( $key );

        if ( ! is_file( $path ) ) {
            return [];
        }

        return json_decode( file_get_contents( $path ), true );
    }

    /**
     * Write the cache data to a file.
     *
     * @param  string $key   Holds the cache key.
     * @param  mixed  $value Holds the value to cache.
     * @return $this Return the cache adapter instance.
     */
    private function write( string $key, mixed $value ) : static
    {
        file_put_contents( $this->getPath( $key ), json_encode( $value ) );

        return $this;
    }
}
