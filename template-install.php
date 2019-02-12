#!/usr/bin/env php
<?php

set_time_limit(0);
ob_implicit_flush();
error_reporting(E_NOTICE);

use helpers\ArrayHelper;
use helpers\ConsoleHelper;

require __DIR__ . '/vendor/autoload.php';

/**
 * Class InstallTemplate
 */
class InstallTemplate
{
    /**
     * @var array|mixed
     */
    private $config = [];
    /**
     * @var array|mixed
     */
    private $globalConf = [];
    /**
     * @var array
     */
    private $projectConf = [];
    /**
     * @var string
     */
    private $selected = '';

    /**
     * @return string
     */
    private function getConfigPath()
    {
        return __DIR__ . '/template/config.yaml';
    }

    /**
     * @return string
     */
    private function getGlobalConfigPath()
    {
        return __DIR__ . '/projects/config.yaml';
    }

    /**
     * @return string
     */
    private function getTemplateDirPath()
    {
        return __DIR__ . '/template/' . $this->selected;
    }

    /**
     * @param string $file
     * @return string
     */
    private function getProjectsDirPath($file = '')
    {
        return __DIR__ . '/projects/' . $this->selected . '/' . $file;
    }

    /**
     * InstallTemplate constructor.
     */
    public function __construct()
    {
        $this->config = yaml_parse_file($this->getConfigPath());
        $this->globalConf = yaml_parse_file($this->getGlobalConfigPath());
    }

    /**
     *
     */
    public function run()
    {
        $this->selected = ConsoleHelper::getInstance()
            ->getListSelected(
                'Select template',
                ArrayHelper::getValue($this->config, 'templates', [])
            );
        mkdir(__DIR__ . '/projects', 0777);
        $this->recurseCopy($this->getTemplateDirPath(), $this->getProjectsDirPath());
        $this->rewriteConfig();
    }

    /**
     *
     */
    private function rewriteConfig()
    {
        $dir = ConsoleHelper::getInstance()->readParams('dir');
        $docker_dir = ConsoleHelper::getInstance()->readParams('docker_dir');

        $this->projectConf = yaml_parse_file($this->getProjectsDirPath('config.yaml'));
        $this->projectConf['dir'] = $dir;
        $this->projectConf['docker_dir'] = $docker_dir;

        $this->globalConf['projects'][] = $this->selected;
        $this->globalConf['projects'] = array_unique($this->globalConf['projects']);
        $this->globalConf['projects'] = array_values($this->globalConf['projects']);
    }

    /**
     *
     */
    public function __destruct()
    {
        yaml_emit_file($this->getProjectsDirPath('config.yaml'), $this->projectConf);
        yaml_emit_file($this->getGlobalConfigPath(), $this->globalConf);

        ConsoleHelper::getInstance()->writeln('Installed [' . $this->selected . ']');
    }

    /**
     * @param $src
     * @param $dst
     */
    private function recurseCopy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurseCopy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}

(new InstallTemplate())->run();