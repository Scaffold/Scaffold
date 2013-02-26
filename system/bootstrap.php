<?php defined('SCAFFOLD') or die();

/**
 * Scaffold Framework bootstrap
 *
 * Do not edit this file, instead, create a custom
 * bootstrap.php file in the application folder.
 * Editing this file could lead to unexpected results.
 */

/**
 * We need to load functions.php because it's contents
 * are used elsewhere in Scaffold.
 */
require(SYSTEM . 'functions.php');

/**
 * Include our Autoloader
 *
 * This is the only class we should be including manually.
 */
load_file('classes' . DS . 'autoload.php');

/**
 * Run the Autoloader
 *
 * This will enable us to use classes without having
 * to manually include them everytime.
 */
Autoload::run();

/**
 * Register with our error class
 */
Error::register();

/**
 * Register framework services
 */
Service::register('dummy', function() {
    return new ServiceDummy();
});

Service::singleton('request', function($uri = null) {
    return new Request($uri);
});

Service::singleton('response.json', function() {
    return new Response();
}, true);

Service::singleton('response.default', function() {
    return Service::get('response.json');
});

Service::singleton('controller', function($controller, Request $request = null, Response $response = null) {
    $request  = ($request !== null) ? $request : Service::get('request');
    $response = ($response !== null) ? $response : Service::get('response');

    $controller = 'Controller' . ucfirst($controller);

    return new $controller($request, $response);
});

Service::singleton('router.blank', function() {
    return new Router();
}, true);

Service::singleton('router.default', function() {
    $router = Service::get('router');

    // automatically send response
    $router->add_hook(function($controller) {
        if ($controller instanceof Controller) $controller->response->send();
    });

    $router->all('/', 'index');
    $router->all('/:controller/:resource/:id');
    $router->all('/:controller/:id');

    return $router;
});

Service::singleton('database.builder', function($type) {
    $class = 'DatabaseQueryBuilder';

    switch ($type) {

        case 'sqlite':
            $class .= 'Sqlite';
        break;

        // @TODO Sensible defaults...
        default:
            $class .= 'SQL';
        break;
    }

    return new $class;
});

Service::register('database.driver', function($config = false) {

    if (!$config) {
        $config = Service::get('config')->get('database');
    }

    $parent = 'DatabaseDriver';
    $type = $config['type'];
    $class = $parent . ucfirst($type);
    $driver = strtolower($type);

    if (!Autoload::load($class) && in_array($driver, PDO::getAvailableDrivers())) {
        $class = $parent . 'PDO';
    }

    $builder = Service::get('database.builder', $type);

    return new $class($builder, $config);
});

Service::singleton('database', function() {
    return new Database(Service::get('config'));
});

// If we have a custom bootloader for the application, load that.
if (file_exists(APPLICATION . 'bootstrap.php')) {
    include APPLICATION . 'bootstrap.php';
}