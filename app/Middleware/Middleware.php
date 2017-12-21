<?php

namespace App\Middleware;

use Interop\Container\ContainerInterface;

abstract class Middleware
{
    /**
     * The container instance.
     *
     * @var \Interop\Container\ContainerInterface
     */
    protected $c;

    /**
     * Set up middleware to have access to the container.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->c = $container;
    }

    public function __get($property)
    {
        if ($this->c->{$property}) {
            return $this->c->{$property};
        }
    }
}