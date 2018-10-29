<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 12.10.18
 * Time: 12:26
 */

namespace scripts;


/**
 * Class Mongo
 * @package scripts
 *
 * @method array|null create
 * @method array|null update
 */
final class Mongo extends Scripts
{
    /**
     * @return array
     */
    public function getListScripts()
    {
        return [
            'create' => [
                'field' => 'create mongo schema',
                'script' => "vagrant ssh -c 'cd /www && sudo php bin/console doctrine:mongodb:schema:create && sudo php bin/console doctrine:mongodb:schema:create --env=test'"
            ],
            'update' => [
                'field' => 'mongo update',
                'script' => "vagrant ssh -c 'cd /www && sudo php ./bin/console doctrine:mongo:schema:update'",
            ],
        ];
    }
}