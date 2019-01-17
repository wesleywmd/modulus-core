<?php
namespace Modulus\Bootstrap\Container;

use Modulus\Bootstrap\Config\Config;
use Modulus\Bootstrap\Config\SystemDefinition;
use Modulus\Bootstrap\System;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class Builder
{
    private $loaderLocation;

    private $commandDefinitionFactory;

    public function __construct($loaderLocation)
    {
        $this->loaderLocation = $loaderLocation;
        $this->commandDefinitionFactory = new CommandDefinitionFactory();
    }

    /**
     * @param Config $configuration
     * @return ContainerBuilder
     * @throws \Exception
     */
    public function build(Config $configuration, $loader_object)
    {
        $containerBuilder = new ContainerBuilder();
        $this->autowire($containerBuilder, $configuration->get("autowiring"));
        $loaderDefinition = $containerBuilder->getDefinition($loader_object);
        $loaderDefinition->setPublic(true);
        foreach( $configuration->get("applications") as $key=>$application ) {
            $id = "applications.".$key;
            $applicationName = $this->resolveApplicationName($application);
            $applicationDefinition = new Definition($application["class"], [$applicationName, $application["version"]]);
            $loaderDefinition->addMethodCall("register", [$id, new Reference($id)]);
            foreach( $application["commands"] as $name=>$command ) {
                $commandDefinition = $this->commandDefinitionFactory->create($name, $command);
                $commandId = $id . str_replace(":", ".", $name);
                $containerBuilder->setDefinition($commandId, $commandDefinition);
                $applicationDefinition->addMethodCall("add", [new Reference($commandId)]);
            }
            $containerBuilder->setDefinition($id, $applicationDefinition);
        }
        $loaderDefinition->addMethodCall("load");
        $containerBuilder->setDefinition($loader_object, $loaderDefinition);
        $containerBuilder->compile();
        return $containerBuilder;
    }

    /**
     * @param $containerBuilder
     * @param $autowires
     * @throws \Exception
     */
    private function autowire($containerBuilder, $autowires)
    {
        $yamlFileLoader = new YamlFileLoader($containerBuilder, new FileLocator($this->loaderLocation));
        $prototype = new Definition();
        $prototype->setAutowired(true);
        $prototype->setAutoconfigured(true);
        $prototype->setPublic(false);
        foreach( $autowires as $namespace=>$resource ) {
            $yamlFileLoader->registerClasses($prototype, $namespace, $resource["resource"], $resource["excludes"]);
        }
    }

    private function resolveApplicationName($application)
    {
        if( empty($application["banner"]) ) {
            return $application["name"];
        }

        $name = "";
        foreach($application["banner"] as $line ) {
            if( !empty($name) ) {
                $name .= "\n";
            }
            $name .= $line;
        }
        return $name;
    }
}