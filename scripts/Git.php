<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 29.10.18
 * Time: 12:35
 */

namespace scripts;

/**
 * Class Git
 * @package scripts
 *
 * @method array|null pull
 * @method array|null push
 * @method array|null status
 */
class Git extends Scripts
{
    /**
     * @return array
     */
    public function getListScripts()
    {
        return [
            'pull' => ['field' => 'git pull', 'script' =>"git pull"],
            'push' => ['field' => 'git push', 'script' =>"git push"],
            'status' => ['field' => 'git status', 'script' =>"git status"],
        ];
    }
}
