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
namespace Omega\Cache\ServiceProvider;

/**
 * @use
 */
use Omega\Application\Application;
use Omega\Cache\Factory\CacheFactory;
use Omega\Container\ServiceProvider\ServiceProviderInterface;
use Omega\Support\Facades\Config;

/**
 * Cache service provider class.
 *
 * The `CacheServiceProvider` class is responsible for providing cache-related
 * services to the framework. It defines the available cache drivers and their
 * factory methods.
 *
 * @category    Omega
 * @package     Cache
 * @subpackage  ServiceProvider
 * @link        https://omegacms.github.io
 * @author      Adriano Giovannini <omegacms@outlook.com>
 * @copyright   Copyright (c) 2024 Adriano Giovannini. (https://omegacms.github.io)
 * @license     https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 * @version     1.0.0
 */
class CacheServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritdoc
     * 
     * @param  Application $application Holds the main application container to which services are bound.
     * @return void This method does not return a value.
     */
    public function bind( Application $application ) : void
    {
        $application->alias( 'cache', function () {
            $config  = Config::get( 'cache' );
            $default = $config[ 'default' ];

            return ( new CacheFactory())->create( $config[ $default ] );
        });
    }
}
