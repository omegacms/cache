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
namespace Omega\Cache;

/**
 * @use
 */
use Closure;
use Omega\Cache\Adapter\CacheAdapterInterface;
use Omega\Cache\Exceptions\UnsupportedAdapterException;
use Omega\ServiceProvider\ServiceProviderInterface;

/**
 * Cache factory class.
 *
 * The `CacheFactory` class is responsible for registering and creating cache
 * drivers based on configurations. It acts as a factory for different cache
 * drivers and provides a flexible way to connect to various caching systems.
 *
 * @category    Omega
 * @package     Omega\Cache
 * @link        https://omegacms.github.io
 * @author      Adriano Giovannini <omegacms@outlook.com>
 * @copyright   Copyright (c) 2022 Adriano Giovannini. (https://omegacms.github.io)
 * @license     https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 * @version     1.0.0
 */
class CacheFactory implements ServiceProviderInterface
{
    /**
     * Registered cache drivers.
     *
     * @var array $drivers Holds an array of driver aliases and their factory closures.
     */
    protected array $drivers;

    /**
     * @inheritdoc
     *
     * @param  string  $alias  Holds the driver alias, e.g., `file` or `memcache`.
     * @param  Closure $driver Holds a closure that creates an instance of the cache driver.
     * @return $this
     */
    public function register( string $alias, Closure $driver ) : static
    {
        $this->drivers[ $alias ] = $driver;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @param  array $config Holds the configuration options, including the `type` to specify the driver.
     * @return CacheAdapterInterface An instance of the configured cache driver.
     * @throws UnsupportedAdapterException if the driver type is not defined or unrecognised.
     */
    public function bootstrap( array $config ) : CacheAdapterInterface
    {
        if ( ! isset( $config[ 'type' ] ) ) {
            throw new UnsupportedAdapterException(
                'Type is not defined.'
            );
        }

        $type = $config[ 'type' ];

        if ( isset( $this->drivers[ $type ] ) ) {
            return $this->drivers[ $type ]( $config );
        }

        throw new UnsupportedAdapterException(
            'Unrecognised type.'
        );
    }
}