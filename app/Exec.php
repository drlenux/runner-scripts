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
        TopScripts::getInstance()->set($script);
        $this->exec(
            ArrayHelper::getValue($script, 'field', ''),
            ArrayHelper::getValue($script, 'script', [])
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

            Console::updateProgress($done++, $total, $title . '-' . $field);
            $this->exec($field, $script);
        }
        Console::endProgress();
        ConsoleHelper::readParams('press enter');
    }

    /**
     * @param $field
     * @param $script
     * @throws \yii\console\Exception
     */
    private function exec($field, $script)
    {
        while (@ ob_end_flush()) ;
        ConsoleHelper::writeln();
        ConsoleHelper::writeln('>[' . $field . '] : run ...');
        $command = 'cd ' . App::getInstance()->getDir() . App::getInstance()->getProject() . ' && ' . $script;
        $proc = popen($command, 'r');
        while (!feof($proc)) {
            Console::stdout(fread($proc, 4096));
            @ flush();
        }
        ConsoleHelper::writeln();
        ConsoleHelper::writeln('>[' . $field . '] : done.');
    }
}