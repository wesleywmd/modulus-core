<?php
namespace Modulus\Bootstrap;

use Modulus\Bootstrap\Config\ApplicationConfiguration;
use Modulus\Bootstrap\Config\ApplicationLoader;
use Modulus\Bootstrap\Config\Config;
use Modulus\Bootstrap\Config\SystemDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Container
{
    /** @var  ContainerBuilder $container */
    private $container;

    private $system;

    private $cache;

    private $builder;

    /**
     * Container constructor.
     * @param System $system
     * @throws FilesystemException
     * @throws \Exception
     */
    public function __construct(System $system)
    {
        $this->system = $system;

        $this->cache = new Container\Cache(
            $this->system->getCacheLocation($this->system->get(SystemDefinition::CACHE_FILE)),
            $this->system->get(SystemDefinition::CACHE_OBJECT),
            $this->system->get(SystemDefinition::IS_DEBUG)
        );

        $this->builder = new Container\Builder($system->getEtcLocation());
    }

    /**
     * @throws \Exception
     */
    public function refresh()
    {
        if( ! $this->cache->isFresh() ) {
            $definition = new ApplicationConfiguration();
            $loader = new ApplicationLoader($this->system->getAppRoot());
            $configuration = new Config($definition, $loader->getLocations());
            $build = $this->builder->build($configuration, $this->system->get(SystemDefinition::APP_LOADER));
            $this->cache->write($build);
        }

        $this->cache->loadObject();

        if( is_null($this->container) ) {
            $this->container = $this->cache->create();
        }
    }

    /**
     * @param bool $lazy
     * @return object
     * @throws \Exception
     */
    public function getLoader($lazy = true)
    {
        if( is_null($this->container) ) {
            if( $lazy ) {
                $this->refresh();
            } else {
                throw new \Exception("Container not Loaded");
            }
        }

        return $this->container->get($this->system->get(SystemDefinition::APP_LOADER));
    }
}