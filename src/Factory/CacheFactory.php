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
namespace Omega\Cache\Factory;

/**
 * @use
 */

use Omega\Cache\Adapter\CacheAdapterInterface;
use Omega\Cache\Adapter\FileAdapter;
use Omega\Cache\Adapter\MemcacheAdapter;
use Omega\Cache\Adapter\MemoryAdapter;
use Omega\Cache\Exception\UnsupportedAdapterException;
/**
 * Cache factory class.
 *
 * The `CacheFactory` class is responsible for registering and creating cache
 * drivers based on configurations. It acts as a factory for different cache
 * drivers and provides a flexible way to connect to various caching systems.
 *
 * @category    Omega
 * @package     Cache
 * @subpackage  Factory
 * @link        https://omegacms.github.io
 * @author      Adriano Giovannini <omegacms@outlook.com>
 * @copyright   Copyright (c) 2024 Adriano Giovannini. (https://omegacms.github.io)
 * @license     https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 * @version     1.0.0
 */
class CacheFactory implements CacheFactoryInterface
{
    /**
     * @inheritdoc
     * 
     * @param ?array $config Holds an optional configuration array that may be used to influence the creation of the object. If no configuration is provided, default settings may be applied.
     * @return mixed Return the created object or value. The return type is flexible, allowing for any type to be returned, depending on the implementation.
     */
    public function create( ?array $config = null ) : CacheAdapterInterface
    {
        if ( ! isset( $config[ 'type' ] ) ) {
            throw new UnsupportedAdapterException(
                'Type is not defined.'
            );
        }

        return match( $config[ 'type' ] ) {
            'file'     => new FileAdapter( $config ),
            'memcache' => new MemcacheAdapter( $config ),
            'memory'   => new MemoryAdapter( $config ),
            default    => throw new UnsupportedAdapterException( 'Unrecognised type.' )
        };
    }    
}
