<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 23.10.18
 * Time: 11:39
 */

namespace scripts;

/**
 * Class MemCached
 * @package scripts
 *
 * @method array|null memcacheRestart static
 * @method array|null memcacheStart static
 * @method array|null memcacheStop static
 */
class MemCached extends Scripts
{
    /**
     * @return array
     */
    public function getListScripts()
    {
        return [
            'stop' => [
                'field' => 'memcached: stop',
                'script' => "vagrant ssh -c 'sudo systemctl stop memcached'",
            ],
            'start' => [
                'field' => 'memcached: start',
                'script' => "vagrant ssh -c 'sudo systemctl start memcached'",
            ],
            'restart' => [
                'field' => 'memcached: restart',
                'script' => "vagrant ssh -c 'sudo systemctl restart memcached'",
            ],
        ];
    }
}