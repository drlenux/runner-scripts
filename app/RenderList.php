<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 26.11.18
 * Time: 16:30
 */

namespace app;


class RenderList extends Singleton
{
    /**
     * @param array $scripts
     * @param bool $isScript
     * @throws \yii\console\Exception
     */
    public function run(array $scripts, $isScript = false)
    {
        if (!$isScript) {
            $this->renderList(TopScripts::getInstance()->getTop(), 'a', false, false);
        }
        $this->renderList($scripts, 1, $isScript);
    }

    /**
     * @param array $list
     * @param int $numbering
     * @param bool $isBack
     * @param bool $addButton
     * @throws \yii\console\Exception
     */
    private function renderList(array $list, $numbering = 1, $isBack = false, $addButton = true)
    {
        if (!count($list)) return;

        for ($i = 0; $i < count($list); $i++) {
            ConsoleHelper::writeln('[' . $numbering . ']: ' . $list[$i]['name']);
            $numbering++;
        }

        if ($addButton) $this->addButton($isBack);
        ConsoleHelper::writeln("");
    }

    /**
     * @param bool $isBack
     * @throws \yii\console\Exception
     */
    private function addButton($isBack = false)
    {
        if ($isBack) {
            ConsoleHelper::writeln('[*]: run all');
            ConsoleHelper::writeln('[0]: back');
        } else {
            ConsoleHelper::writeln('[0]: exit');
        }
    }
}