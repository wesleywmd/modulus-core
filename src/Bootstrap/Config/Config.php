<?php
namespace Modulus\Bootstrap\Config;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class Config
{
    private $idGlue = ":";

    private $values;

    public function __construct($definition, $paths, $safe = true, $idGlue = ":")
    {
        $this->idGlue = $idGlue;

        $processor = new Processor();
        $loadables = [];
        foreach( $paths as $path ) {
            if( is_file($path) || !$safe ) {
                $loadables[] = Yaml::parseFile($path);
            }
        }
        $this->values = $processor->processConfiguration($definition, $loadables);
    }

    /**
     * @param $key
     * @return array|null
     * @throws \Exception
     */
    public function get($key)
    {
        return $this->getRecursive($key, $this->values);
    }

    /**
     * @param array|string $key
     * @param array $subject
     * @param bool $safe
     * @return array|null
     * @throws \Exception
     */
    private function getRecursive($key, $subject, $safe = true)
    {
        $id = $this->splitKey($key);
        $current = array_shift($id);
        if( !isset($subject[$current]) ) {
            if( $safe ) {
                return null;
            } else {
                throw new \Exception("Config Key doesn't exist");
            }
        }
        if( count($id) > 0 ) {
            return $this->getRecursive($id, $subject[$current]);
        }
        return $subject[$current];
    }

    /**
     * @param array|string $id
     *
     * @return array
     * @throws \Exception
     */
    private function splitKey($id)
    {
        if( is_array($id) ) {
            return $id;
        }

        if( is_string($id) ) {
            return explode($this->idGlue, $id);
        }

        throw new \Exception("Config ID mush be an array or string");
    }
}