<?php
// Default routes distributed with Alloy
require $kernel->config('alloy.path.config') . '/routes.php';


// Override 'defualt' route to point to 'Blog' module
$router->route('default', '/')
    ->defaults(array('module' => 'Blog', 'action' => 'index', 'format' => 'html'));