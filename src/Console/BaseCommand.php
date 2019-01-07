<?php
namespace Modulus\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommand extends \Symfony\Component\Console\Command\Command
{
    /** @var ServiceInterface $initializeService */
    protected $service;

    protected $title;

    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    public function setService(ServiceInterface $service)
    {
        $this->service = $service;
    }

    public function setTitle($title)
    {
        $this->service->setTitle($title);
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->service->initialize($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->service->interact($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->service->execute($input, $output);
    }
}