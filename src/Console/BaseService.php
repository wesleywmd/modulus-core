<?php
namespace Modulus\Console;

use Modulus\Components\Style\IoFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BaseService implements ServiceInterface
{
    protected $ioFactory;

    protected $title;

    public function __construct(IoFactory $ioFactory)
    {
        $this->ioFactory = $ioFactory;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $io = $this->ioFactory->create($input, $output);
        if( !empty($this->title) ) {
            $io->title($this->title);
        }
    }

    public function interact(InputInterface $input, OutputInterface $output)
    {
        // TODO: Implement interact() method.
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO: Implement execute() method.
    }
}