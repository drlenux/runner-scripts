<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 11.02.19
 * Time: 11:11
 */

namespace helpers;

use app\App;
use app\ConsoleDi;
use app\DI;
use app\Singleton;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class ConsoleHelper
 * @package helpers
 */
class ConsoleHelper extends Singleton
{
    use DI;

    /**
     *
     */
    protected function init()
    {
        $this->setDi('console', new ConsoleDi());
    }


    /**
     * @param $str
     * @param string $char
     */
    public function fillLine($str, $char = '=')
    {
        $maxLineSize = App::getInstance()->getConfig('maxLineSize');
        if (strlen($str) + 2 >= $maxLineSize) {
            $text = $str;
        } else {
            $after = intval(($maxLineSize - 2 - strlen($str)) / 2);
            $before = $maxLineSize - ($after + strlen($str));
            $text = implode('', array_fill(1, $before, $char));
            $text .= '[' . $str . ']';
            $text .= implode('', array_fill(1, $after, $char));
        }

        $this->getDi('console')->writeln($text);
    }

    public function readParams($name, $placeHolder = null)
    {
        $helper = new QuestionHelper();
        $question = new Question($name . ': ', $placeHolder);
        $res = $helper->ask(new ArgvInput(), $this->getDi('console'), $question);

        if ('' === $res) {
            $res = $placeHolder;
        }
        return $res;
    }

    /**
     * @param string $text
     */
    public function writeln($text = '')
    {
        if (!is_string($text))
            throw new LogicException('text must be string');

        $this->getDi('console')->writeln($text);
    }

    public function write($text = '')
    {
        if (!is_string($text))
            throw new LogicException('text must be string');

        $this->getDi('console')->write($text);
    }

    public function clearScreen()
    {
        echo "\033[2J";
    }

    /**
     * @param string $question
     * @param array $list
     * @param int $default
     * @param string $errorMessage
     * @return mixed
     */
    public function getListSelected($question = '', array $list = [], $default = 0, $errorMessage = '')
    {
        $helper = new QuestionHelper();
        $question = new ChoiceQuestion($question, $list, $default);
        $question->setErrorMessage($errorMessage);

        return $helper->ask(new ArgvInput(), $this->getDi('console'), $question);
    }
}