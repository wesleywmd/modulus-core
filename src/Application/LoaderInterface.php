<?php
namespace Modulus\Application;

interface LoaderInterface
{
    public function register($id, $application);
    public function load();
}