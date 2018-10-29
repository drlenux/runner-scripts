<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 12.10.18
 * Time: 12:22
 */

namespace scripts;

/**
 * Class Composer
 * @package scripts
 *
 * @method array|null install static
 */
final class Composer extends Scripts
{
    /**
     * @return array
     */
    public function getListScripts()
    {
        return [
            'install' => ['field' => 'composer install', 'script' => "vagrant ssh -c 'cd /www && sudo php ./composer.phar install'"]
        ];
    }
}