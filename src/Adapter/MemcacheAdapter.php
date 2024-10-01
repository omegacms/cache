<?php
/**
 * Part of Omega CMS - Cache Package
 *
 * @link       https://omegacms.github.io
 * @author     Adriano Giovannini <omegacms@outlook.com>
 * @copyright  Copyright (c) 2024 Adriano Giovannini. (https://omegacms.github.io)
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
use Memcached;
use Omega\Cache\Exception\UnsupportedAdapterException;

/**
 * Memcache adapter class.
 *
 * The `MemcachedAdapter` class implements a cache adapter that uses the Memcached
 * extension to interact with a Memcached server. It extends the AbstractCacheAdapter
 * class and provides methods to check, retrieve, store, and manage cached data using
 * Memcached.
 *
 * @category    Omega
 * @package     Cache
 * @subpackage  Adapter
 * @link        https://omegacms.github.io
 * @author      Adriano Giovannini <omegacms@outlook.com>
 * @copyright   Copyright (c) 2024 Adriano Giovannini. (https://omegacms.github.io)
 * @license     https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 * @version     1.0.0
 */
class MemcacheAdapter extends AbstractCacheAdapter
{
    /**
     * Memcached object.
     *
     * @var Memcached $memcache Holds an object of memcached.
     */
    private Memcached $memcache;

    /**
     * MemcacheAdapter class constructor.
     *
     * Initializes the MemcacheAdapter with configuration options and connects to
     * the Memcached server.
     *
     * @param  array $config Holds an array of configuration options.
     * @return void
     * @throws UnsupportedAdapterException if the Memcached extension is not enabled or installed, preventing the caching functionality from being used.
     */
    public function __construct( array $config )
    {
        if ( ! extension_loaded( 'memcached' ) ) {
            throw new UnsupportedAdapterException(
                'Memcached extension is not enabled. Please make sure to install the Memcached extension'
              . 'to enable caching functionality and improve performance. You can install it using your system\'s package manager or '
              . 'by following the instructions provided in the Memcached documentation.'
            );
        }

        parent::__construct( $config );

        $this->memcache = new Memcached();
        $this->memcache->addServer( $config[ 'host' ], $config[ 'port' ] );
    }

    /**
     * @inheritdoc
     *
     * @param  string $key Holds the cache key to check.
     * @return bool Returns true if the key exists in the cache, otherwise false.
     */
    public function has( string $key ) : bool
    {
        return $this->memcache->get( $key ) !== false;
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
        if ( $value = $this->memcache->get( $key ) ) {
            return $value;
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
    public function put( string $key, mixed $value, int $seconds = null ) : static
    {
        if ( ! is_int( $seconds ) ) {
            $seconds = (int)$this->config[ 'seconds' ];
        }

        $this->memcache->set( $key, $value, time() + $seconds );

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
        $this->memcache->delete( $key );

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return $this Return the cache adapter instance.
     */
    public function flush() : static
    {
        $this->memcache->flush();

        return $this;
    }
}