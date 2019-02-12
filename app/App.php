<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 25.10.18
 * Time: 12:34
 */

namespace app;

use helpers\ArrayHelper;
use helpers\ConsoleHelper;
use Symfony\Component\Console\Exception\LogicException as Exception;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

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

    /**\
     * @param string $key
     * @param string $project
     * @param null $default
     * @return mixed
     */
    public function getConfig($key, $project = '', $default = null)
    {
        if (!is_string($key))
            throw new Exception('key must be string');

        if (strlen($project)) {
            $key = $project . '.' . $key;
        }

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

        foreach ($config['projects'] as $project) {
            $this->config[$project] = yaml_parse_file(BASE_DIR . '/projects/' . $project . '/config.yaml');
        }

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

        $path = BASE_DIR . '/projects/' . $this->project . '/' . $name . '.yaml';

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

        ConsoleHelper::getInstance()->clearScreen();
        ConsoleHelper::getInstance()->fillLine('Projects');
        $this->project = ConsoleHelper::getInstance()->getListSelected('Project name', $this->getConfig('projects', '', []), 0);
        $this->dir = $this->getConfig('dir', $this->project);
        $this->docker_dir = $this->getConfig('docker_dir', $this->project);
        (new Runner());
    }
}
