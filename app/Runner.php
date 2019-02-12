<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 25.10.18
 * Time: 13:00
 */

namespace app;

use helpers\ConsoleHelper;

/**
 * Class Runner
 * @package app
 */
class Runner
{
    /**
     * Runner constructor.
     */
    public function __construct()
    {
        $components = App::getInstance()->getConfig('scripts', App::getInstance()->getProject(), []);
        do {
            ConsoleHelper::getInstance()->clearScreen();
            $isContinue = $this->run('Commands list', $components);
        } while ($isContinue);
    }

    /**
     * @param $title
     * @param array $scripts
     * @param bool $isScript
     * @return bool
     */
    private function run($title, array $scripts, $isScript = false)
    {
        ConsoleHelper::getInstance()->fillLine($title);
        if ($isScript) {
           $list = array_keys($scripts);
           $list[] = 'back';
        } else {
            $list = $scripts;
        }

        $list[] = 'exit';
        $select = ConsoleHelper::getInstance()->getListSelected('Run', $list);

        if ('exit' === $select) {
            return 0;
        }
        if ('back' === $select) {
            return 1;
        }
        if ($isScript) {
            Exec::getInstance()->run($scripts[$select]);
            return $this->run($title, App::getInstance()->getScript($title), $isScript);
        } else {
            return $this->run($select, App::getInstance()->getScript($select),true);
        }
    }
}