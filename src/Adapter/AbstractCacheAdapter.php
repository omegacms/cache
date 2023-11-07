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
 * Abstract cache adapter class.
 *
 * The `AbstractCacheAdapter` class provides a foundation for implementing cache
 * adapters. It implements the methods defined in the CacheAdapterInterface and
 * adds some common properties and behaviors.
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
abstract class AbstractCacheAdapter implements CacheAdapterInterface
{
    /**
     * Configuration array for the cache adapter.
     *
     * @var array $config Holds an array of configuration options.
     */
    public array $config;

    /**
     * Cached data storage.
     *
     * @var array $cached Holds an array to store cached data.
     */
    public array $cached = [];

    /**
     * AbstractCacheAdapter class constructor.
     *
     * @param  array $config Holds the configuration options for the cache adapter.
     * @return void
     */
    public function __construct( array $config )
    {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     *
     * @param  string $key Holds the cache key to check.
     * @return bool Returns true if the key exists in the cache, otherwise false.
     */
    abstract public function has( string $key ) : bool;

    /**
     * @inheritdoc
     *
     * @param  string $key     Holds the cache key to retrieve.
     * @param  mixed  $default Holds the default value to return if the key is not found.
     * @return mixed Return the cached value if found, otherwise the default value.
     */
    abstract public function get( string $key, mixed $default = null ) : mixed;

    /**
     * @inheritdoc
     *
     * @param  string $key     Holds the cache key to store.
     * @param  mixed  $value   Holds the value to store in the cache.
     * @param  ?int   $seconds Holds the number of seconds until the cache item expires (null for no expiration).
     * @return $this Return the cache adapter instance.
     */
    abstract public function put( string $key, mixed $value, ?int $seconds = null ) : static;

    /**
     * @inheritdoc
     *
     * @param  string $key Holds the cache key to remove.
     * @return $this Return the cache adapter instance.
     */
    abstract public function forget( string $key ) : static;

    /**
     * @inheritdoc
     *
     * @return $this Return the cache adapter instance.
     */
    abstract public function flush() : static;
}
