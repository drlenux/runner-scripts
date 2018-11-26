<?php

namespace app;

/**
 * Class Select
 * @package app
 */
class Select
{
    /**
     * @var string|null
     */
    private $value;

    /**
     * Select constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->value = ConsoleHelper::readParams($name);
    }

    /**
     * @return int
     */
    public function getInt()
    {
        return intval($this->value);
    }

    /**
     * @return string|null
     */
    public function get()
    {
        return $this->value;
    }
}