#!/usr/bin/env php
<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 25.10.18
 * Time: 12:31
 */

set_time_limit(0);
ob_implicit_flush();

define('BASE_DIR', __DIR__);

require __DIR__ . '/vendor/autoload.php';

$config = yaml_parse_file(__DIR__ . '/projects/config.yaml');

\app\App::getInstance()->setConfig($config)->run();