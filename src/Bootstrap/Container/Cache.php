<?php
namespace Modulus\Bootstrap\Container;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class Cache
{
    private $containerConfigCache;

    private $location;

    private $cache_object;

    public function __construct($location, $cache_object, $is_debug)
    {
        $this->containerConfigCache = new ConfigCache($location, $is_debug);
        $this->location = $location;
        $this->cache_object = $cache_object;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function create()
    {
        return new $this->cache_object;
    }

    public function isFresh()
    {
        return $this->containerConfigCache->isFresh();
    }

    /**
     * @param ContainerBuilder $build
     * @throws \Exception
     */
    public function write(ContainerBuilder $build)
    {
        $dumper = new PhpDumper($build);
        $dump = $dumper->dump(["class" => $this->cache_object]);

        $this->containerConfigCache->write($dump, $build->getResources());
    }

    /**
     * @throws \Exception
     */
    public function loadObject()
    {
        if( ! class_exists($this->cache_object) ) {
            require_once $this->location;
        }
    }
}