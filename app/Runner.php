<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 25.10.18
 * Time: 13:00
 */

namespace app;

use scripts\Scripts;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

/**
 * Class Runner
 * @package app
 */
class Runner
{
    /**
     * @var array
     */
    private $runnersList = [];

    /**
     * Runner constructor.
     * @throws \yii\console\Exception
     */
    public function __construct()
    {
        $components = App::getInstance()->getConfig('components', []);
        do {
            $isContinue = $this->run('Commands list', $components);
        } while ($isContinue);
    }

    /**
     * @param array $lists
     * @param bool $isClass
     * @throws \yii\console\Exception
     */
    private function getList(array $lists, $isClass = false)
    {
        $back = ($isClass) ? 'back' : 'exit';
        ConsoleHelper::writeln();
        ConsoleHelper::writeln(">0 [{$back}]");
        for ($i = 1; $i <= count($lists); $i++) {
            ConsoleHelper::writeln(">{$i} [{$lists[$i-1]}]");
            $this->runnersList[$i] = $lists[$i - 1];
        }
        if ($isClass) {
            ConsoleHelper::writeln('>* [run all]');
        }
    }

    /**
     * @param $title
     * @param array $scripts
     * @param Scripts|null $class
     * @return bool
     * @throws \yii\console\Exception
     */
    private function run($title, array $scripts, $class = null)
    {
        ConsoleHelper::fillLine($title);
        $this->getList(array_keys($scripts), (null !== $class));
        $run = ConsoleHelper::readParams('run');

        $run = trim($run);
        if ('0' === strval($run))
            return false;

        if (null !== $class && '*' === $run) {
            $this->runAll($title, $class);
            return true;
        }

        $commandName = $this->runnersList[$run];
        if (null === $class) {
            /** @var Scripts $realScript */
            $realScript = $scripts[$commandName];
            $list = array_flip($realScript::getInstance()->getList());
            foreach ($list as $key => &$value) {
                $value = $key;
            }
            $run = $this->run($commandName, $list, $realScript);
            if ($run) {
                ConsoleHelper::readParams('press enter to continue');
            }
        } else {
            $exec = $class::getInstance()->{$commandName}();

            $this->exec(
                ArrayHelper::getValue($exec, 'field', ''),
                ArrayHelper::getValue($exec, 'script', '')
            );
        }
        return true;
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

    /**
     * @param $title
     * @param Scripts $class
     * @throws \yii\console\Exception
     */
    private function runAll($title, $class)
    {
        $done = 0;
        $total = count($this->runnersList);
        Console::startProgress($done, $total, $title);
        foreach ($this->runnersList as $run) {
            Console::updateProgress($done++, $total, $title . '-' . $run);
            $exec = $class::getInstance()->{$run}();
            $this->exec(
                ArrayHelper::getValue($exec, 'field', ''),
                ArrayHelper::getValue($exec, 'script', '')
            );
        }
        Console::endProgress();
    }
}