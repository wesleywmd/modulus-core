<?php
namespace Modulus;

class Bootstrap
{
    private $system;

    public function __construct($app_root)
    {
        $this->system = new Bootstrap\System($app_root);
    }

    /**
     * @throws Bootstrap\FilesystemException
     * @throws \Exception
     */
    public function launch()
    {
        $container = new Bootstrap\Container($this->system);
        $container->getLoader();
    }

    /**
     * @throws Bootstrap\FilesystemException
     */
    public function flushcache()
    {
        $cacheLocation = $this->system->getCacheLocation();
        foreach( new \DirectoryIterator($cacheLocation) as $fileInfo ) {
            if( ! $fileInfo->isDot() ) {
                unlink($fileInfo->getPathname());
            }
        }
    }
}