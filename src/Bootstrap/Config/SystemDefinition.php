<?php
namespace Modulus\Bootstrap\Config;

class SystemDefinition implements \Symfony\Component\Config\Definition\ConfigurationInterface
{
    const APP_HOME = "app_home";
    const APP_LOADER = "app_loader";
    const LOCATION = "location";
    const IS_DEBUG = "is_debug";
    const CACHE_OBJECT = "cache_object";
    const CACHE_DIRECTORY = "cache_directory";
    const CACHE_FILE = "cache_file";

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new \Symfony\Component\Config\Definition\Builder\TreeBuilder();
        $treeBuilder->root("system")
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode(self::APP_HOME)->defaultValue(".modulus")->end()
                ->scalarNode(self::APP_LOADER)->defaultValue("Modulus\\Application\\RuntimeLoader")->end()
                ->scalarNode(self::LOCATION)->defaultValue("etc")->end()
                ->booleanNode(self::IS_DEBUG)->defaultFalse()->end()
                ->scalarNode(self::CACHE_OBJECT)->defaultValue("ModulusCacheContainer")->end()
                ->scalarNode(self::CACHE_DIRECTORY)->defaultValue("cache")->end()
                ->scalarNode(self::CACHE_FILE)->defaultValue("container.php")->end()
            ->end();
        return $treeBuilder;
    }
}