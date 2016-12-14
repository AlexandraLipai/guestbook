<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.11.2016
 * Time: 14:38
 */
define ('MESSAGES_PER_PAGE',5);
define ('PAGINATION_INDENT',5);

spl_autoload_register(function($name) {
    require_once $name . '.php';});

$controller = new Controller();
$controller->run();
?>