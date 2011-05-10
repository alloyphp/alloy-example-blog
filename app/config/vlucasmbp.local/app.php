<?php
// Configuration
$cfg = require dirname(__DIR__) . '/app.php';

// Debug?
$cfg['app']['debug'] = false;

// In Development Mode?
$cfg['app']['mode']['development'] = true;

// Layout to wrap around response (if Alloy_Layout plugin enabled)
$cfg['app']['layout'] = array(
    'enabled' => true,
    'template' => 'app'
);

// Database (Optional - only used if module loads a mapper)
$cfg['app']['database']['master'] = array(
    'adapter' => 'mysql',
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'alloy_example_rest',
    'options' => array(
        PDO::ERRMODE_EXCEPTION => true,
        PDO::ATTR_PERSISTENT => false,
        PDO::ATTR_EMULATE_PREPARES => true
    )
);

return $cfg;