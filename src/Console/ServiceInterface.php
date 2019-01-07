<?php
namespace Modulus\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface ServiceInterface
{
    public function setTitle($title);
    public function initialize(InputInterface $input, OutputInterface $output);
    public function interact(InputInterface $input, OutputInterface $output);
    public function execute(InputInterface $input, OutputInterface $output);
}