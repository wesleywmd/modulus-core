<?php
namespace Modulus\Bootstrap\Container;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class CommandDefinitionFactory
{
    private $argumentModes = [
        "required" => InputArgument::REQUIRED,
        "optional" => InputArgument::OPTIONAL,
        "array" => InputArgument::IS_ARRAY
    ];

    private $optionModes = [
        "none" => InputOption::VALUE_NONE,
        "required" => InputOption::VALUE_REQUIRED,
        "optional" => InputOption::VALUE_OPTIONAL,
        "array" => InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY
    ];

    public function create($name, $command)
    {
        $commandDefinition = new Definition($command["class"], [$name]);
        $commandDefinition->addMethodCall("setDescription",[$command["description"]]);
        $commandDefinition->addMethodCall("setHelp",[$command["help"]]);
        $commandDefinition->addMethodCall("setService", [new Reference($command["service"])]);
        $commandDefinition->addMethodCall("setTitle", [$command["title"]]);

        foreach( $command["arguments"] as $arg=>$argument ) {
            $commandDefinition->addMethodCall("addArgument", [
                $arg,
                $this->argumentModes[$argument["mode"]],
                $argument["description"],
                $argument["default"]
            ]);
        }

        foreach( $command["options"] as $opt=>$option ) {
            $commandDefinition->addMethodCall("addOption", [
                $opt,
                $option["shortcut"],
                $this->optionModes[$option["mode"]],
                $option["description"],
                $option["default"]
            ]);
        }

        return $commandDefinition;
    }
}