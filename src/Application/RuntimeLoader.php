<?php
namespace Modulus\Application;

class RuntimeLoader implements LoaderInterface
{
    private $applications = [];

    public function register($id, $application)
    {
        $this->applications[$id] = $application;
    }

    public function load()
    {
        $this->applications["applications.runtime"]->run();
    }
}