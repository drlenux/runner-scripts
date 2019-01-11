<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 26.11.18
 * Time: 16:21
 */

namespace app;


use yii\helpers\ArrayHelper;
use yii\helpers\Console;

/**
 * Class Exec
 * @package app
 */
class Exec extends Singleton
{
    /**
     * @param array $script
     * @throws \yii\console\Exception
     */
    public function run(array $script)
    {
        $options = ArrayHelper::getValue($script, 'options', []);
        $isDocker = ArrayHelper::getValue($script, 'isDocker', 'no') === 'yes';

        foreach ($options as $key => &$value) {
            $value = ConsoleHelper::readParams($key, $value);
        }

        TopScripts::getInstance()->set($script);
        $this->exec(
            ArrayHelper::getValue($script, 'field', ''),
            ArrayHelper::getValue($script, 'script', []),
            $isDocker,
            $options
        );
        ConsoleHelper::readParams('press enter');
    }

    /**
     * @param $title
     * @param array $scripts
     * @throws \yii\console\Exception
     */
    public function runAll($title, array $scripts)
    {
        $done = 1;
        $total = count($scripts);
        Console::startProgress($done, $total, $title);
        foreach ($scripts as $run) {
            $field = ArrayHelper::getValue($run, 'field', '');
            $script = ArrayHelper::getValue($run, 'script', '');
            $options = ArrayHelper::getValue($script, 'options', []);
            $isDocker = ArrayHelper::getValue($script, 'isDocker', 'no') === 'yes';

            foreach ($options as $key => &$value) {
                $value = ConsoleHelper::readParams($key, $value);
            }

            Console::updateProgress($done++, $total, $title . '-' . $field);
            $this->exec($field, $script, $isDocker, $options);
        }
        Console::endProgress();
        ConsoleHelper::readParams('press enter');
    }

    /**
     * @param $field
     * @param $script
     * @param $isDocker
     * @param array $options
     * @throws \yii\console\Exception
     */
    private function exec($field, $script, $isDocker, array $options)
    {
        while (@ ob_end_flush()) ;
        ConsoleHelper::writeln();
        ConsoleHelper::writeln('>[' . $field . '] : run ...');

        foreach ($options as $key => $value) {
            $script = str_replace("{%{$key}%}", $value, $script);
        }

        $command = 'cd ' . App::getInstance()->getDir() . App::getInstance()->getProject() . ($isDocker) ? App::getInstance()->getDockerDir() : '' . ' && ' . $script;
        ConsoleHelper::writeln($command);
        $proc = popen($command, 'r');
        while (!feof($proc)) {
            Console::stdout(fread($proc, 4096));
            @ flush();
        }
        ConsoleHelper::writeln();
        ConsoleHelper::writeln('>[' . $field . '] : done.');
    }
}