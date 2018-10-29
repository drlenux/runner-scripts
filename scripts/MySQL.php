<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 12.10.18
 * Time: 12:51
 */

namespace scripts;

/**
 * Class MySQL
 * @package scripts
 *
 * @method array|null migrate static
 * @method array|null rollback static
 * @method array|null dropDB static
 */
final class MySQL extends Scripts
{
    /**
     * @return array
     */
    public function getListScripts()
    {
        return [
            'dropDB' => [
                'field' => 'drop database',
                'script' => "vagrant ssh -c 'sudo mysql -u root -e \"DROP DATABASE db_site\"'"
            ],
            'rollback' => [
                'field' => 'mysql rollback',
                'script' => "vagrant ssh -c 'cd /www/migration && sudo bash ./vendor/bin/phinx rollback'"
            ],
            'migrate' => [
                'field' => 'mysql migrate',
                'script' => "vagrant ssh -c 'cd /www/migration && sudo bash ./vendor/bin/phinx migrate'"
            ],
        ];
    }
}