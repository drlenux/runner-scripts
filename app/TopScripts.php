<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 26.11.18
 * Time: 15:26
 */

namespace app;


use yii\helpers\ArrayHelper;

/**
 * Class TopScripts
 * @package app
 */
final class TopScripts extends Singleton
{
    /**
     * @var array|mixed
     */
    private $top = [];

    /**
     * @var string
     */
    private $topFileName;

    /**
     * TopScripts constructor.
     */
    protected function __construct()
    {
        $this->topFileName = BASE_DIR . '/config/top-scripts.yaml';
        if (!file_exists($this->topFileName)) {
            $this->save();
        }
        $this->top = yaml_parse_file($this->topFileName);
    }

    /**
     * @param array $script
     */
    private function update(array $script)
    {
        $hash = base64_encode(json_encode($script));
        $count = intval(ArrayHelper::getValue($this->top, $hash, 0));
        $this->top[$hash] = ++$count;
    }

    /**
     *
     */
    private function save()
    {
        yaml_emit_file($this->topFileName, $this->top);
    }

    /**
     * @param array $script
     */
    public function set(array $script)
    {
        $this->update($script);
        $this->save();
    }

    /**
     * @return array|mixed
     */
    public function getTop()
    {
        arsort($this->top);
        $top = array_slice($this->top, 0, 6);
        $res = [];

        foreach (array_keys($top) as $hash) {
            $tmp = ArrayHelper::toArray(json_decode(base64_decode($hash)));
            $tmp['name'] = $tmp['field'];
            $res[] = $tmp;
        }

        return $res;
    }
}