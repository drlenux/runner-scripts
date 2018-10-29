<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 12.10.18
 * Time: 12:23
 */

namespace scripts;

use app\Singleton;
use yii\helpers\ArrayHelper;

/**
 * Class Scripts
 * @package app\scripts
 */
abstract class Scripts extends Singleton
{
    /**
     * @return string[]
     */
    public function getList()
    {
        return array_keys($this->getListScripts());
    }

    /**
     * @param $name
     * @param $arguments
     * @return array
     */
    public function __call($name, $arguments)
    {
        return ArrayHelper::getValue($this->getListScripts(), $name, []);
    }

    /**
     * @return array
     */
    abstract public function getListScripts();
}
