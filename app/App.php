<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 25.10.18
 * Time: 12:34
 */

namespace app;

use yii\console\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

/**
 * Class App
 * @package app
 */
class App extends Singleton
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * @var string
     */
    private $dir = '';

    /**
     * @var string
     */
    private $project = '';

    /**
     * @param $key
     * @param null $default
     * @return mixed
     * @throws Exception
     */
    public function getConfig($key, $default = null)
    {
        if (!is_string($key))
            throw new Exception('key must be string');

        return ArrayHelper::getValue($this->config, $key, $default);
    }

    /**
     * @param array $config
     * @return $this
     * @throws Exception
     */
    public function setConfig(array $config)
    {
        if (count($this->config))
            throw new Exception('config already installed');

        $this->config = ArrayHelper::toArray($config);
        return $this;
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @return string
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @throws Exception
     */
    public function run() {
        if (!count($this->config))
            throw new Exception('config is empty');
        Console::clearScreen();
        ConsoleHelper::fillLine('Scripts');
        $this->dir = ConsoleHelper::readParams('Project dir', $this->getConfig('default.dir'));
        $this->project = ConsoleHelper::readParams('Project name', $this->getConfig('default.project'));
        (new Runner());
    }
}
