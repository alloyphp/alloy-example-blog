<?php
// Configuration
$cfg = array();

// Debug?
$cfg['debug'] = true;

// In Development Mode?
$cfg['mode']['development'] = true;

// Layout to wrap around response (if Alloy_Layout plugin enabled)
$cfg['layout'] = array(
    'enabled' => true,
    'template' => 'app'
);

// Database (Optional - only used if module loads a mapper)
$cfg['database']['master'] = array(
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