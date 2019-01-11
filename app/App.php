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

    private $docker_dir = '';

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
     * @return string
     */
    public function getDockerDir()
    {
        return $this->docker_dir;
    }

    /**
     * @param string $name
     * @return array
     * @throws Exception
     */
    public function getScript($name) {
        if (!is_string($name)) {
            throw new Exception('name must be a string');
        }

        $path = BASE_DIR . '/scripts/' . $name . '.yaml';

        if (!file_exists($path)) {
            throw new Exception('Script [' . $name . '] not found');
        }

        return yaml_parse_file($path);
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
        $this->docker_dir = ConsoleHelper::readParams('Project name', $this->getConfig('default.docker_dir'));
        (new Runner());
    }
}
