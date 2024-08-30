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
namespace Omega\Cache\ServiceProvider;

/**
 * @use
 */
use Omega\Cache\CacheFactory;
use Omega\Cache\Adapter\FileAdapter;
use Omega\Cache\Adapter\MemcacheAdapter;
use Omega\Cache\Adapter\MemoryAdapter;
use Omega\Container\ServiceProvider\AbstractServiceProvider;
use Omega\Container\ServiceProvider\ServiceProviderInterface;

/**
 * Cache service provider class.
 *
 * The `CacheServiceProvider` class is responsible for providing cache-related
 * services to the framework. It defines the available cache drivers and their
 * factory methods.
 *
 * @category    Omega
 * @package     Omega\Cache
 * @subpackage  Omega\Cache\ServiceProvider
 * @link        https://omegacms.github.io
 * @author      Adriano Giovannini <omegacms@outlook.com>
 * @copyright   Copyright (c) 2022 Adriano Giovannini. (https://omegacms.github.io)
 * @license     https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 * @version     1.0.0
 */
class CacheServiceProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     *
     * @return string Return the service name, which is `cache`.
     */
    protected function name() : string
    {
        return 'cache';
    }

    /**
     * @inheritdoc
     *
     * @return ServiceProviderInterface Return an instance of ServiceProviderInterface.
     */
    protected function factory() : ServiceProviderInterface
    {
        return new CacheFactory();
    }

    /**
     * @inheritdoc
     *
     * @return array Return an associative array where keys are driver names and values are factory callbacks.
     */
    protected function drivers() : array
    {
        return [
            'file'     => function ( $config ) {
                return new FileAdapter( $config );
            },
            'memcache' => function ( $config ) {
                return new MemcacheAdapter( $config );
            },
            'memory'   => function ( $config ) {
                return new MemoryAdapter( $config );
            },
        ];
    }
}