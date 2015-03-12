<?php
namespace Songbird;

trait ContainerResolverTrait
{
    public function resolve($alias, $args = [])
    {
        return $this->getContainer()->get($alias, $args);
    }
}