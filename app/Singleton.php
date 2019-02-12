<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 25.10.18
 * Time: 12:35
 */

namespace app;

/**
 * Class Singleton
 * @package app
 */
abstract class Singleton
{
    /**
     * @var static|null
     */
    private static $instance = [];

    /**
     * @return static|null
     */
    public static function getInstance()
    {
        $getCalledClass = get_called_class();
        if (empty(self::$instance[$getCalledClass])) {
            self::$instance[$getCalledClass] = new static();
        }

        return self::$instance[$getCalledClass];
    }

    protected function init()
    {

    }

    /**
     * Singleton constructor.
     */
    protected function __construct()
    {
        $this->init();
    }

    /**
     *
     */
    protected function __clone()
    {
    }

    /**
     *
     */
    protected function __sleep()
    {
    }

    /**
     *
     */
    protected function __wakeup()
    {
    }
}
