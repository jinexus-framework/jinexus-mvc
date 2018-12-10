<?php
namespace JiNexus\Mvc\Base;

/**
 * Interface BaseInterface
 * @package JiNexus\Mvc\Base
 */
interface BaseInterface
{
    /**
     * Getters and Setters
     *
     * @param $property
     * @param array $arguments
     * @return mixed
     */
    public function __call($property, array $arguments);
}