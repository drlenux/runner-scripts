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
 */
class InstallAll extends Scripts
{
    /**
     * @return array
     */
    public function getListScripts()
    {
        var_dump([
            'vagrant_reload' => Vagrant::reload(),
            'vagrant_provision' => Vagrant::provision(),
            'drop_db' => MySQL::dropDB(),
            'composer_install' => Composer::install(),
            'db_migrate' => MySQL::migrate(),
            'mongo_update' => Mongo::update(),
        ]);
        return [
            'vagrant_reload' => Vagrant::reload(),
            'vagrant_provision' => Vagrant::provision(),
            'drop_db' => MySQL::dropDB(),
            'composer_install' => Composer::install(),
            'db_migrate' => MySQL::migrate(),
            'mongo_update' => Mongo::update(),
        ];
    }
}
