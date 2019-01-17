<?php
namespace Modulus\Bootstrap;

use Modulus\Bootstrap\Config\SystemDefinition;

class System
{
    private $app_root;

    private $filesystem;

    private $config;

    public function __construct($app_root)
    {
        $this->app_root = $app_root;
        $this->filesystem = new Filesystem();
        $paths = [$this->getAppLocation("etc", "system.yaml")];
        $this->config = new Config\Config(new SystemDefinition(), $paths);
    }

    public function getAppRoot()
    {
        return $this->app_root;
    }

    /**
     * @param array|string $key
     * @return mixed
     * @throws \Exception
     */
    public function get($key)
    {
        return $this->config->get($key);
    }

    /**
     * @return bool
     */
    public function getIsWindows()
    {
        return $this->filesystem->getIsWindows();
    }

    /**
     * @return string
     */
    public function getAppLocation()
    {
        return $this->filesystem->getLocation($this->app_root, func_get_args());
    }

    /**
     * @return string
     * @throws FilesystemException
     * @throws \Exception
     */
    public function getHomeLocation()
    {
        $homeLocation = $this->filesystem->getHomeRoot();
        $homeLocation .= DIRECTORY_SEPARATOR . $this->config->get(SystemDefinition::APP_HOME);
        return $this->filesystem->getLocation($homeLocation, func_get_args());
    }

    /**
     * @return string
     * @throws FilesystemException
     * @throws \Exception
     */
    public function getCacheLocation()
    {
        $cacheLocation = $this->getHomeLocation();
        $cacheLocation .= DIRECTORY_SEPARATOR . $this->config->get(SystemDefinition::CACHE_DIRECTORY);
        return $this->filesystem->getLocation($cacheLocation, func_get_args());
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getEtcLocation()
    {
        return $this->getAppLocation($this->get(SystemDefinition::LOCATION));
    }
}