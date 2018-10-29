<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 12.10.18
 * Time: 12:50
 */

namespace scripts;

/**
 * Class Cron
 * @package scripts
 *
 * @method array|null restart static
 * @method array|null start static
 * @method array|null stop static
 */
final class Cron extends Scripts
{
    /**
     * @return array
     */
    public function getListScripts()
    {
        return [
            'stop' => [
                'field' => 'cron stop',
                'script' => "vagrant ssh -c 'sudo /sbin/service crond stop'"
            ],
            'start' => [
                'field' => 'cron start',
                'script' => "vagrant ssh -c 'sudo /sbin/service crond start'"
            ],
            'restart' => [
                'field' => 'cron restart',
                'script' => "vagrant ssh -c 'sudo /sbin/service crond restart'"
            ],
        ];
    }
}
