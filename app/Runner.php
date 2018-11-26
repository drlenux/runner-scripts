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
            Console::clearScreen();
            $isContinue = $this->run('Commands list', $components);
        } while ($isContinue);
    }

    /**
     * @param array $list
     * @param bool $isBack
     * @throws \yii\console\Exception
     */
    private function renderList(array $list, $isBack = false)
    {
        for ($i = 1; $i <= count($list); $i++) {
            ConsoleHelper::writeln('[' . $i . ']: ' . $list[$i-1]['name']);
        }

        if ($isBack) {
            ConsoleHelper::writeln('[*]: run all');
            ConsoleHelper::writeln('[0]: back');
        } else {
            ConsoleHelper::writeln('[0]: exit');
        }
    }

    /**
     * @param $title
     * @param array $scripts
     * @param bool $isScript
     * @return bool
     * @throws \yii\console\Exception
     */
    private function run($title, array $scripts, $isScript = false)
    {
        ConsoleHelper::fillLine($title);
        $this->modifiedArray($scripts);
        $this->renderList($scripts, $isScript);
        $select = new Select('run');

        if ('*' === $select->get() && $isScript) {
            $this->runAll($title, $scripts);
            ConsoleHelper::readParams('press enter');
            return $this->run($title, $scripts, $isScript);
        }

        if (0 === $select->getInt()) {
            if (!$isScript) {
                return false;
            }
            return true;
        }

        if ($isScript) {
            $this->exec(
                $scripts[$select->getInt() - 1]['field'],
                $scripts[$select->getInt() - 1]['script']
            );
            ConsoleHelper::readParams('press enter');
            return $this->run($title, $scripts, $isScript);
        } else {
            $selectName = $scripts[$select->getInt() - 1]['name'];
            return $this->run($selectName, App::getInstance()->getScript($selectName),true);
        }

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
     * @param array $scripts
     * @throws \yii\console\Exception
     */
    private function runAll($title, array $scripts)
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
    }

    public function modifiedArray(array &$list)
    {
        $newArray = [];
        foreach ($list as $key => $value) {
            if (is_array($value) ) {
                $newArray[] = array_merge($value, ['name' => $key]);
            } else {
                $newArray[] = ['name' => $value];
            }
        }
        $list = $newArray;
    }
}