<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 12.10.18
 * Time: 12:14
 */

namespace scripts;

use yii\helpers\ArrayHelper;

/**
 * Class Vagrant
 * @package scripts
 *
 * @method array|null up
 * @method array|null halt
 * @method array|null status
 * @method array|null reload
 * @method array|null provision
 */
final class Vagrant extends Scripts
{
    /**
     * @return array
     */
    public function getListScripts()
    {
        return [
            'halt' => ['field' => 'vagrant halt', 'script' => "vagrant halt"],
            'up' => ['field' => 'vagrant up', 'script' => "vagrant up"],
            'status' => ['field' => 'vagrant status', 'script' => "vagrant status"],
            'reload' => ['field' => 'vagrant reload', 'script' => "vagrant reload"],
            'provision' => ['field' => 'vagrant provision', 'script' => "vagrant provision"],
        ];
    }
}