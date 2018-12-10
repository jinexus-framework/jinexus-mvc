<?php
namespace JiNexus\Mvc\Model;

/**
 * Class ViewModel
 * @package JiNexus\Mvc\Model
 */
class ViewModel extends AbstractModel
{
    /**
     * ViewModel variables
     *
     * @var array
     */
    protected $variables = [];

    /**
     * ViewModel constructor
     * @param array $variables
     */
    public function __construct($variables = [])
    {
        $this->setVariables($variables);
    }

    /**
     * Get all variables
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Set all variables
     *
     * @param array $variables
     */
    public function setVariables($variables = [])
    {
        $this->variables = $variables;
    }
}