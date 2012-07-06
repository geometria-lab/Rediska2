<?php

/**
 * Setup autoloading
 */
function Rediska2Test_Autoloader($class)
{
    $class = ltrim($class, '\\');

    if (!preg_match('#^(Rediska2(Test)?|PHPUnit)(\\\\|_)#', $class)) {
        return false;
    }

    $segments = explode('\\', $class);
    $ns       = array_shift($segments);

    switch ($ns) {
        case 'Rediska2':
            $file = dirname(__DIR__) . '/library/Rediska2/';
            break;
        case 'Rediska2Test':
            $file = __DIR__ . '/Rediska2/';
            break;
        default:
            $file = false;
            break;
    }

    if ($file) {
        $file .= implode('/', $segments) . '.php';
        if (file_exists($file)) {
            return include_once $file;
        }
    }

    return false;
}
spl_autoload_register('Rediska2Test_Autoloader', true, true);