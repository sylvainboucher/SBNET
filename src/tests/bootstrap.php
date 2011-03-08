<?php
$rootPath = realpath(dirname(__DIR__));
if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH',
        $rootPath . '/application');
}
if (!defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', 'testing');
}
set_include_path(implode(PATH_SEPARATOR, array(
    '.',
    $rootPath . '/library',
    get_include_path(),
)));
/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap();
$bootstrap = $application->getBootstrap();
$front = $bootstrap->getResource('FrontController');
$front->setParam('bootstrap', $bootstrap);
$front->setRequest(new Zend_Controller_Request_HttpTestCase());