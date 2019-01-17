<?php
namespace Modulus\Application;

class InstallerLoader implements LoaderInterface
{
    private $applications = [];

    public function register($id, $application)
    {
        $this->applications[$id] = $application;
    }

    public function load()
    {
        $application = ( $this->isLoaded() ) ? "applications.runtime" : "applications.installer";
        $this->applications[$application]->run();
    }

    private function isLoaded()
    {
        return true;
    }
}