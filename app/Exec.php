<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 26.11.18
 * Time: 16:21
 */

namespace app;


use helpers\ArrayHelper;
use helpers\ConsoleHelper;

/**
 * Class Exec
 * @package app
 */
class Exec extends Singleton
{
    /**
     * @param array $script
     */
    public function run(array $script)
    {
        $options = ArrayHelper::getValue($script, 'options', []);
        $isDocker = ArrayHelper::getValue($script, 'isDocker', 'no') === 'yes';

        ConsoleHelper::getInstance()->writeln(ArrayHelper::getValue($script, 'script', ''));

        foreach ($options as $key => &$value) {
            $value = ConsoleHelper::getInstance()->readParams($key, $value);
        }

        $this->exec(
            ArrayHelper::getValue($script, 'field', ''),
            ArrayHelper::getValue($script, 'script', []),
            $isDocker,
            $options
        );
        ConsoleHelper::getInstance()->readParams('press enter');
    }

    /**
     * @param $field
     * @param $script
     * @param $isDocker
     * @param array $options
     */
    private function exec($field, $script, $isDocker, array $options)
    {
        while (@ ob_end_flush()) ;
        ConsoleHelper::getInstance()->writeln();
        ConsoleHelper::getInstance()->writeln('>[' . $field . '] : run ...');

        $docker_dir = ($isDocker) ? App::getInstance()->getDockerDir() : '';

        $command = 'cd ' . App::getInstance()->getDir() . $docker_dir . ' && ' . $script;

        foreach ($options as $key => $value) {
            $command = str_replace("{%{$key}%}", $value, $command);
        }
        ConsoleHelper::getInstance()->writeln("\r\n> " . $command . "\r\n");
        ConsoleHelper::getInstance()->writeln("\r\n");

        $proc = popen($command, 'r');
        while (!feof($proc)) {
            ConsoleHelper::getInstance()->write(fread($proc, 4096));
            @ flush();
        }
        ConsoleHelper::getInstance()->writeln();
        ConsoleHelper::getInstance()->writeln('>[' . $field . '] : done.');
    }
}