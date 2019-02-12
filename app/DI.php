<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 11.02.19
 * Time: 11:24
 */

namespace app;


use helpers\ArrayHelper;
use Symfony\Component\Console\Exception\LogicException;

trait DI
{
    protected $di = [];

    protected function setDi($name, IDI $di)
    {
        if (ArrayHelper::keyExists($name, $this->di)) {
            throw new LogicException('di name is exist');
        }

        ArrayHelper::setValue($this->di, $name, $di);
    }

    protected function getDi($name)
    {
        return ArrayHelper::getValue($this->di, $name);
    }
}