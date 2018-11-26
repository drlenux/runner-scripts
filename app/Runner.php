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
     * @param int $numbering
     * @param bool $isBack
     * @throws \yii\console\Exception
     */


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
        RenderList::getInstance()->run($scripts, $isScript);
        $select = new Select('run');

        if ('*' === $select->get() && $isScript) {
            Exec::getInstance()->runAll($title, $scripts);
            return $this->run($title, $scripts, $isScript);
        }

        if (0 === $select->getInt()) {
            if (!$isScript) {
                return false;
            }
            return true;
        }
        $script = $scripts[$select->getInt() - 1];
        if ($isScript) {
            Exec::getInstance()->run($script);
            return $this->run($title, App::getInstance()->getScript($title), $isScript);
        } else {
            $selectName = $script['name'];
            return $this->run($selectName, App::getInstance()->getScript($selectName),true);
        }

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