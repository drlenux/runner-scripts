<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 25.10.18
 * Time: 15:15
 */

namespace scripts;

/**
 * Class InstallAll
 * @package scripts
 *
 * @method array vagrant_reload
 * @method array vagrant_provision
 * @method array drop_db
 * @method array composer_install
 * @method array db_migrate
 * @method array mongo_update
 */
class InstallAll extends Scripts
{
    /**
     * @return array
     */
    public function getListScripts()
    {
        return [
            'vagrant_reload' => Vagrant::getInstance()->reload(),
            'vagrant_provision' => Vagrant::getInstance()->provision(),
            'drop_db' => MySQL::getInstance()->dropDB(),
            'composer_install' => Composer::getInstance()->install(),
            'db_migrate' => MySQL::getInstance()->migrate(),
            'mongo_update' => Mongo::getInstance()->update(),
        ];
    }
}
