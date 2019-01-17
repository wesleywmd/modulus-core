<?php
namespace Modulus\Bootstrap\Config;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class ApplicationLoader
{
    private $app_root;

    public function __construct($app_root)
    {
        $this->app_root = $app_root;
    }

    public function getLocations()
    {
        $array = $this->getVendorLocations();
        $array[] = $this->app_root.DIRECTORY_SEPARATOR."etc".DIRECTORY_SEPARATOR."modulus.yaml";
        return $array;
    }

    public function load()
    {
        $locations = $this->getLocations();
        $processor = new Processor();
        $configuration = new ApplicationConfiguration();
        $rawConfigurations = [];

        foreach( $locations as $location ) {
            $rawConfigurations[] = Yaml::parseFile($location);
        }

        return $processor->processConfiguration($configuration, $rawConfigurations);
    }

    private function getVendorLocations()
    {
        $configs = [];
        $pattern = implode(DIRECTORY_SEPARATOR, [$this->app_root, "vendor", "*", "*", "composer.json"]);
        foreach( glob($pattern) as $filePath ) {
            $jsonContent = file_get_contents($filePath);
            $jsonArray = json_decode($jsonContent, true);

            $modulusFile = implode(DIRECTORY_SEPARATOR, [dirname($filePath), "etc", "modulus.yaml"]);

            if( isset($jsonArray["type"]) && $jsonArray["type"] === "modulus-module" && is_file($modulusFile) ) {
                $configs[] = $modulusFile;
            }
        }
        return $configs;
    }
}