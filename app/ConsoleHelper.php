<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 25.10.18
 * Time: 12:35
 */

namespace app;

use yii\console\Exception;
use yii\helpers\Console;

/**
 * Class ConsoleHelper
 * @package app
 */
class ConsoleHelper
{
    const NEW_LINE ="\r\n";

    /**
     * @param $str
     * @param string $char
     * @throws \yii\console\Exception
     */
    public static function fillLine($str, $char = '=')
    {
        $maxLineSize = App::getInstance()->getConfig('maxLineSize');
        $text = '';
        if (strlen($str) + 2 >= $maxLineSize) {
            $text = $str;
        } else {
            $after = intval(($maxLineSize - 2 - strlen($str)) / 2);
            $before = $maxLineSize - ($after + strlen($str));
            $text = implode('', array_fill(1, $before, $char));
            $text .= '[' . $str . ']';
            $text .= implode('', array_fill(1, $after, $char));
        }
        $text .= self::NEW_LINE;
        Console::stdout($text);
    }

    public static function readParams($name, $placeHolder = null)
    {
        Console::stdout('>[' . $name. ']');
        if (null !== $placeHolder) {
            Console::stdout(' (' . $placeHolder . ')');
        }
        Console::stdout(' : ');
        $res = Console::stdin();
        Console::stdout(self::NEW_LINE);

        if ('' === $res) {
            $res = $placeHolder;
        }
        return $res;
    }

    /**
     * @param string $text
     * @throws Exception
     */
    public static function writeln($text = '')
    {
        if (!is_string($text))
            throw new Exception('text must be string');

        Console::stdout($text . self::NEW_LINE);
    }
}