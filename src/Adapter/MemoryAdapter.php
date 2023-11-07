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
use function is_int;
use function time;

/**
 * Memory adapter class.
 *
 * The `MemoryAdapter` class implements a cache adapter that stores cached data in
 * memory. It extends the AbstractCacheAdapter class and provides methods to check,
 * retrieve, store, and manage cached data in memory.
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
class MemoryAdapter extends AbstractCacheAdapter
{
    /**
     * MemoryAdapter class constructor.
     *
     * Initializes the MemoryAdapter with configuration options.
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
        return isset( $this->cached[ $key ] ) && $this->cached[ $key ][ 'expires' ] > time();
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

        $this->cached[ $key ] = [
            'value' => $value,
            'expires' => time() + $seconds
        ];

        return $this;
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

        return $this;
    }
}
