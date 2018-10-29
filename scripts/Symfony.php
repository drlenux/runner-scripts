<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 12.10.18
 * Time: 12:54
 */

namespace scripts;

/**
 * Class Symfony
 * @package scripts
 *
 * @method array|null manifest_generate static
 * @method array|null generate_translate static
 */
final class Symfony extends Scripts
{
    /**
     * @return array
     */
    public function getListScripts()
    {
        return [
            'manifest_generate' => [
                'field' => 'manifest generate',
                'script' => "vagrant ssh -c 'cd /www && sudo php ./bin/console assets:manifest:generate'",
            ],
            'generate_translate' => [
                'field' => 'generate translate',
                'script' => "vagrant ssh -c 'cd /www && sudo php bin/console translation:convert'"
            ],
            'clear_cache' => [
                'field' => 'clear symfony cache',
                'script' => "vagrant ssh -c 'cd /www && sudo php bin/console cache:clear'"
            ],
        ];
    }
}